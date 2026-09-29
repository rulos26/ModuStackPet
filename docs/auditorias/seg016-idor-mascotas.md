# SEG-016: IDOR crítico en mascotas y mensajes de error expuestos

Fecha: 2026-09-28
Agente: Claude
Rama: `ia/claude/idor-mascotas`

Ver hallazgo original en `docs/auditorias/pruebas-mascotas-documentos.md`
(defecto 1, crítico, y defecto 4 en la parte de `MascotaController`).

## Hallazgo y prueba roja

Las rutas `mascotas.*` solo tenían el middleware `CheckModuleStatus`, sin
`auth` ni `verified`. `MascotaController@show`, `edit` y `destroy` buscaban
la mascota con `Mascota::find($id)` sin comprobar dueño ni rol, y `update`
forzaba `user_id` al usuario autenticado en cada edición (no al valor del
formulario), lo que significa que **cualquier usuario autenticado que
editara cualquier mascota se convertía en su nuevo dueño**, incluidos
Admin/Superadmin al simplemente guardar el formulario de edición de una
mascota ajena.

El commit `53a3af67` añadió `tests/Feature/MascotaAccessControlTest.php`.
Antes de la corrección, 5 de 6 pruebas fallaban:

- Invitado alcanzaba `show`/`edit`/`update`/`destroy` sin redirigir a login.
- Un Cliente ajeno podía ver, editar, actualizar y borrar la mascota de otro
  Cliente.
- Un Paseador podía ver, editar, actualizar y borrar la mascota de un
  Cliente (no tiene por qué tener acceso a ninguna).
- El "cambio de dueño al editar" no se probó como fallo porque el código
  previo lo hacía sistemáticamente (no había defensa que romper), pero la
  prueba quedó para blindar el comportamiento correcto.
- El controlador interpolaba `$e->getMessage()` en los mensajes de sesión
  mostrados al usuario en `store`, `update` y `destroy`.

Solo la prueba de Admin/Superadmin pasaba de entrada, porque antes de la
corrección esos roles ya tenían acceso total (sin política que lo negara).

## Corrección

1. [routes/web.php](../../routes/web.php): el grupo de rutas `mascotas.*`
   ahora exige `auth` y `verified`, igual que ya lo hacía el grupo de
   `mascota-documents.*`.
2. [app/Policies/MascotaPolicy.php](../../app/Policies/MascotaPolicy.php)
   (nueva, descubierta por convención de nombres de Laravel, igual que
   `UserPolicy` — ver limitación de `AuthServiceProvider` no registrado en
   `docs/auditorias/seg011-resource-users.md`): `view`, `update` y `delete`
   devuelven `true` para Superadmin/Admin sobre cualquier mascota, para
   Cliente solo si `mascota->user_id === $user->id`, y `false` para
   cualquier otro rol (Paseador incluido).
3. [app/Http/Controllers/MascotaController.php](../../app/Http/Controllers/MascotaController.php):
   - `show`, `edit` y `destroy` ahora usan Route Model Binding
     (`Mascota $mascota`) en vez de `find($id)`, y llaman
     `$this->authorize('view'|'update'|'delete', $mascota)` antes de
     cualquier lectura o escritura. Una autorización fallida devuelve 403
     automáticamente vía `AuthorizationException`.
   - `update` ya no fuerza `user_id`: se hace `unset($validatedData['user_id'])`
     y se deja el valor original de `$mascota` intacto, tanto si el intento
     de cambio viene del formulario como si venía (como antes) de asignar
     siempre el usuario autenticado.
   - Defecto 4: los `catch` de `store`, `update` y `destroy` ya no
     concatenan `$e->getMessage()` en el mensaje de sesión visible al
     usuario. Ahora registran internamente con
     `Log::error(..., ['user_id' => ..., 'error' => $e->getMessage()])` y
     muestran un mensaje genérico ("Ocurrió un error al ... Intenta
     nuevamente.").

Tras la corrección, las 6 pruebas de `MascotaAccessControlTest` pasan, y la
suite completa (`php artisan test`) da **140 pruebas, 399 aserciones**,
todas en verde (incluye las 9 pruebas de Codex en `MascotaFlowsTest` y
`MascotaDocumentFlowsTest`, que siguen pasando sin cambios). `composer
validate` también pasa.

## Rutas de documentos de mascotas

Se verificó `routes/web.php` líneas 302-310: el grupo `mascota-documents.*`
ya estaba envuelto en `Route::middleware(['auth', 'verified'])` antes de
esta tarea, junto con `CheckModuleStatus::class . ':documentos-mascotas'`.
No fue necesario ningún cambio ahí; ese grupo no exhibía el problema de
rutas sin autenticación que sí tenía `mascotas.*`.

## Restricciones respetadas

No se tocó `MascotaDocumentController` (defectos 2, 3 y 4 de esa clase
quedan para la tarea 017, de Codex). No se modificaron las pruebas de Codex
(`MascotaFlowsTest.php`, `MascotaDocumentFlowsTest.php`); se creó un archivo
de pruebas nuevo (`MascotaAccessControlTest.php`) para esta tarea. No se
tocó `.env` ni dependencias.

## Pendientes y riesgos

- La regla "Paseador: ninguna (por ahora)" solo se aplicó a `view`/`update`/
  `delete` (show/edit/update/destroy), que es lo que pedía explícitamente la
  tarea. No se restringió `store`/`create`: un Paseador autenticado aún
  puede crear una mascota para sí mismo (quedaría como su propia mascota,
  visible solo para él en `index`). Si se decide que Paseador no debe
  interactuar con mascotas en absoluto, es un cambio de alcance aparte.
- `MascotaController@index` sigue filtrando manualmente por rol (no usa la
  policy); se dejó así porque ya filtraba correctamente y no forma parte
  del defecto reportado (el defecto era el acceso directo por id, no el
  listado).
