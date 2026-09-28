---
agente: claude
estado: terminada
rama: ia/claude/idor-mascotas
archivos: [routes/web.php, app/Http/Controllers/MascotaController.php, app/Policies/MascotaPolicy.php, tests/Feature/]
---

# IDOR en mascotas (defecto 1, crítico) y errores expuestos (defecto 4)

Ver docs/auditorias/pruebas-mascotas-documentos.md.

## Reglas de acceso (decisión humana)
- Cliente: solo sus propias mascotas.
- Admin y Superadmin: todas las mascotas.
- Paseador: ninguna (por ahora).
- Nadie cambia el dueño (`user_id`) al editar, tampoco Admin ni Superadmin.
- Invitado: redirección a login.

## Qué hacer
1. Pruebas primero (commit que FALLE), partiendo de la reproducción del informe:
   invitado, Cliente ajeno, Paseador, Admin y Superadmin en show, edit, update
   y destroy; comprobar que el dueño no cambia y que nada se borra sin permiso.
2. Corrección: `auth` y `verified` en el grupo de rutas de mascotas;
   `MascotaPolicy` aplicada en todas las acciones sobre una mascota concreta;
   `update` conserva el `user_id` original.
3. Defecto 4 solo en MascotaController: registrar el error internamente y
   mostrar al usuario un mensaje genérico, sin `$e->getMessage()`.
4. Comprueba si las rutas de documentos de mascotas quedan dentro del grupo
   protegido e infórmalo.

## Entregable
`docs/auditorias/seg016-idor-mascotas.md`.

## Restricciones
- No toques MascotaDocumentController (lo corrige Codex en la tarea 017).
- No modifiques las pruebas de Codex salvo que la corrección lo exija; si lo haces, explícalo.
- No toques `.env` ni dependencias. Todas las pruebas deben pasar.
- Al terminar, vuelve con `git switch --detach origin/main`.

## Handoff
- Agente y fecha: Claude, 2026-09-28.
- Qué se hizo: `auth`+`verified` en el grupo de rutas `mascotas.*`;
  `MascotaPolicy` nueva (Cliente solo lo suyo, Admin/Superadmin todo,
  Paseador nada) aplicada en `show`/`edit`/`update`/`destroy` de
  `MascotaController` vía `$this->authorize(...)`; `update` ya no fuerza
  `user_id`, el dueño original se conserva siempre (antes cualquier editor,
  incluido Admin/Superadmin, se convertía en el nuevo dueño); mensajes de
  error al usuario ya no incluyen `$e->getMessage()` (se registra
  internamente con `Log::error`). Verifiqué que las rutas de
  `mascota-documents.*` ya estaban dentro de `['auth', 'verified']`, no
  requirieron cambio. Detalle completo en
  `docs/auditorias/seg016-idor-mascotas.md`.
- Archivos modificados: `routes/web.php`,
  `app/Http/Controllers/MascotaController.php`,
  `app/Policies/MascotaPolicy.php` (nuevo),
  `tests/Feature/MascotaAccessControlTest.php` (nuevo, no se tocaron los
  archivos de pruebas de Codex), `docs/auditorias/seg016-idor-mascotas.md`
  (nuevo).
- Cómo probarlo: `php artisan test` (140 pruebas, 399 aserciones, todas en
  verde, incluye las 9 pruebas de Codex sobre mascotas/documentos sin
  cambios) y `composer validate`.
- Pendientes y riesgos: no se restringió `store`/`create` para Paseador (la
  tarea pedía corregir show/edit/update/destroy); un Paseador autenticado
  aún puede crear una mascota propia. `MascotaController@index` sigue
  filtrando manualmente por rol en vez de usar la policy (ya filtraba bien,
  no era parte del defecto reportado).
- Preguntas para el humano: ¿Paseador debería poder crear/listar sus
  propias mascotas, o "ninguna" incluye también bloquear `store`/`index`
  para ese rol?
