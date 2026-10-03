# SEG-034 — Consolidar Ciudad / Ciudade (U-10, parte 1 de 3)

Fecha: 2026-10-03 · Agente: Claude · Rama: `ia/claude/consolidar-ciudad`

## Decisión
Se conserva **`App\Models\Ciudad`** (según seg021: usado por Empresa, Cliente, Paseador; tiene `SoftDeletes` que la migración ya soporta; `estado` booleano; relación `empresas()`). `Ciudade` se retira.

## Archivos migrados a `Ciudad`
- `app/Http/Controllers/CiudadController.php` (todas las referencias).
- `app/Models/Departamento.php` (`ciudades()`).
- `resources/views/user/form.blade.php` (selector de ciudad y búsqueda de Bogotá).
- `app/Models/Empresa.php` (solo docblock `@property`).
`CiudadeRequest` y las vistas `resources/views/ciudade/` conservan su nombre (no son el modelo; renombrarlos sería un refactor aparte).

## Movido a `_borrar/`
`app/Models/Ciudade.php` → `_borrar/modelos/Ciudade.php` (carpeta ignorada por git; en el commit figura como eliminación).

## Cambios de comportamiento (intencionales)
- Eliminar una ciudad desde el CRUD pasa de borrado **físico** a **lógico** (`deleted_at`); el listado ya no la muestra. La columna existe desde la migración original.
- `estado` se lee como booleano en vez de entero; las vistas y el controlador comparan con `== 1` y siguen funcionando (cubierto por pruebas).

## Pruebas
`tests/Feature/CiudadCharacterizationTest.php` (10 casos). Primer commit: caracterización con ambos modelos (11 casos, 180 en total, todos pasaban). Tras migrar se reescribieron los casos que mencionaban `Ciudade` para fijar el resultado (decisión intencional, no para esconder una regresión). Suite final: **179 passed** (169 + 10); `composer validate` OK.

## Hallazgos preexistentes (NO corregidos, fuera de alcance; requieren decisión)
1. **CRUD de ciudades roto**: `GET /ciudades/create` y `/ciudades/{id}/edit` dan 500 (`pluck('nombre','id')` sobre `departamentos`, cuya PK es `id_departamento`); `store`/`update` siempre fallan validación (`exists:departamentos,id`). Fijado en pruebas como "broken today".
2. `update(CiudadeRequest, Ciudad $ciudad)`: el parámetro de ruta resource se llama `ciudade`, no `ciudad`, así que el route model binding no aplica.
3. **Las rutas `/ciudades*` no tienen middleware `auth`** (solo `CheckModuleStatus:ciudades`): cualquiera puede listarlas, activarlas/desactivarlas y borrar ciudades inactivas. Conviene una tarea de seguridad aparte (mismo patrón que SEG-001/SEG-010).
4. `vendor/composer/autoload_classmap.php` local sigue listando `Ciudade` (classmap optimizado); se resuelve con `composer dump-autoload`, no afecta a git.

## Ajuste 034b — seguridad de rutas y CRUD roto

### 1. `/ciudades*` sin autenticación (corregido)
Pruebas primero (`tests/Feature/CiudadAccessAndCrudTest.php`, commit que fallaba: 8 de 11): un invitado podía listar, ver, abrir formularios, activar/desactivar y borrar.
Corrección en `routes/web.php`: el grupo de ciudades usa ahora `['auth', 'verified', 'role:Superadmin|Admin', CheckModuleStatus:ciudades]`.
Decisión de rol: Superadmin y Admin, porque los sidebars de ambos enlazan a `ciudades.index`; Cliente y Paseador reciben 403 (probado), invitado va a `login`, usuario sin verificar a `verification.notice`. Si se quiere solo Superadmin, basta cambiar el middleware.
Nota: grupos vecinos (`departamentos`, `barrios`, `razas`, `tipo-documentos`, etc.) siguen sin `auth` en `routes/web.php`; no se tocaron (fuera de alcance), conviene una auditoría aparte.

### 2. CRUD roto (corregido)
- `CiudadController`: `pluck('nombre','id')` → `pluck('nombre','id_departamento')` (create y edit daban 500).
- `CiudadeRequest`: `exists:departamentos,id` → `exists:departamentos,id_departamento`; la regla `unique` usaba `route('ciudade')` y ahora `route('ciudad')?->getKey()`.
- `routes/web.php`: `Route::resource('ciudades', ...)->parameters(['ciudades' => 'ciudad'])`. El parámetro se llamaba `ciudade`, por lo que `update(CiudadeRequest, Ciudad $ciudad)` nunca recibía el modelo enlazado; ahora sí (clave `id_municipio`) y los nombres de ruta no cambian.
Pruebas: formularios renderizan con departamentos; crear, editar (solo la ciudad correcta), conservar el propio nombre, rechazo de departamento inexistente y de duplicados.

### Pruebas
`CiudadCharacterizationTest` ahora actúa como Admin y se eliminaron sus dos pruebas "roto hoy" (sustituidas por las del CRUD funcionando). Suite: **188 passed**.
