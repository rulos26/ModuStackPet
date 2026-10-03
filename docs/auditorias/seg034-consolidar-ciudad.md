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
