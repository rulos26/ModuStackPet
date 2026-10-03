# SEG-035 — Consolidar Sector / Sectore (U-10, parte 2 de 3)

Fecha: 2026-10-03 · Agente: Claude · Rama: `ia/claude/consolidar-sector`
Mismo método que `seg034-consolidar-ciudad.md`.

## Decisión
Se conserva **`App\Models\Sector`**: es el que usa `Empresa::sector()` y `EmpresaController` (formularios), tiene `SoftDeletes` (la migración ya crea `deleted_at`) y la relación `empresas()`. Se le añadió `$perPage = 20` para conservar la paginación que tenía `Sectore` (el índice de `/sectores` sigue mostrando 20 por página).

## Migrado a `Sector`
- `app/Http/Controllers/SectoreController.php` (todas las referencias).
- `app/Models/Empresa.php` (solo docblock).
Sin cambios: `SectoreRequest`, ruta `sectores`, vistas `resources/views/sectore/` (nombres, no son el modelo).

## Movido a `_borrar/modelos/`
`app/Models/Sectore.php` → `_borrar/modelos/Sectore.php` (ignorada por git).

## Auth de rutas (punto 5 de la tarea)
Ya cubierto por 040: el grupo `sectores` tiene `auth + verified + role:Superadmin|Admin` a nivel de ruta (`routes/web.php`). No hacía falta cambio; una prueba lo fija para que no retroceda.

## Bugs de PK / parámetro (punto 6)
- PK: `sectores.id` estándar, sin columna personalizada. Sin bug.
- Parámetro de ruta: `{sectore}` coincide con `Sector $sectore` y con `$this->sectore` en `SectoreRequest` (el `update` y la regla `unique` funcionan; cubierto por pruebas). Sin bug.
- **Bug encontrado y corregido:** `show`, `edit` y `destroy` usaban `find($id)` sin fallo: con un id inexistente `edit`/`destroy` daban 500 y `show` renderizaba vacío. Ahora usan `findOrFail` → 404.

## Cambios de comportamiento (intencionales)
- Eliminar un sector es borrado **lógico** (antes físico). Nota: la regla `unique:sectores,nombre` sigue contando filas borradas lógicamente, así que no se puede reutilizar el nombre de un sector eliminado (misma regla que antes no aplicaba porque la fila desaparecía). Conviene decidir si se ajusta la regla.
- ids inexistentes → 404 en lugar de 500/vacío.

## Pruebas
`tests/Feature/SectorCharacterizationTest.php`. Primer commit: caracterización con ambos modelos (7 casos; 255 en total). Tras migrar se reescribieron los casos que mencionaban `Sectore` para fijar el resultado (decisión intencional). Suite final: **254 passed**.

## Pendientes
- Vista `sectore/show.blade.php` no imprime el nombre (preexistente, sin tocar).
- Mensajes en inglés ("Sectore created successfully") sin tocar.
- Sin prueba manual en navegador.
