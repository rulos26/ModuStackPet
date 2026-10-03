# SEG-036 — Consolidar TipoEmpresa / TiposEmpresa (U-10, parte 3 de 3)

Fecha: 2026-10-03 · Agente: Claude · Rama: `ia/claude/consolidar-tipoempresa`
Mismo método que `seg034-consolidar-ciudad.md` y `seg035-consolidar-sector.md`.

## Decisión
Se conserva **`App\Models\TipoEmpresa`**: lo usan `Empresa::tipoEmpresa()` y `EmpresaController` (formularios), tiene `SoftDeletes` (la migración ya crea `deleted_at`) y la relación `empresas()`. Se añadió `$perPage = 20` para conservar la paginación de `TiposEmpresa`.

## Migrado a `TipoEmpresa`
- `app/Http/Controllers/TiposEmpresaController.php`.
- `app/Models/Empresa.php` (solo docblock).
Sin cambios: `TiposEmpresaRequest`, ruta `tipos-empresas`, vistas `resources/views/tipos-empresa/`.

## Movido a `_borrar/modelos/`
`app/Models/TiposEmpresa.php` → `_borrar/modelos/TiposEmpresa.php` (ignorada por git).

## Revisiones pedidas
- **Auth a nivel de ruta (confirmado, no supuesto):** `routes/web.php` agrupa `tipos-empresas` con `auth + verified + role:Superadmin|Admin + CheckModuleStatus:empresas` (tarea 040). Pruebas: invitado → `login`, Cliente → 403.
- **PK / parámetro de ruta:** PK `id` estándar; el parámetro `{tipos_empresa}` se enlaza correctamente con `$tiposEmpresa` (el `update` funciona, cubierto por pruebas). Sin bug.
- **Bug corregido:** `show`, `edit` y `destroy` usaban `find()` sin fallo (500 con id inexistente); ahora `findOrFail` → 404.
- **Regla `unique`:** `TiposEmpresaRequest` **no tiene regla unique** (solo `required|string`), así que no hay nada que ajustar por SoftDeletes; hoy se aceptan nombres duplicados (fijado en una prueba antes de migrar). No añadí unicidad porque cambia el comportamiento y es decisión de producto; si se quiere, la regla correcta es `unique:tipos_empresas,nombre,{id},id,deleted_at,NULL` (como en sectores).

## Cambios de comportamiento (intencionales)
- Eliminar un tipo de empresa es borrado **lógico** (antes físico).
- ids inexistentes → 404 en lugar de 500.

## Pruebas
`tests/Feature/TipoEmpresaCharacterizationTest.php`. Primer commit: caracterización con ambos modelos (9 casos). Tras migrar se reescribieron los casos que mencionaban `TiposEmpresa` para fijar el resultado (decisión intencional). Suite final: **274 passed**.

## Pendientes
- Mensajes en inglés ("TiposEmpresa created successfully") sin tocar.
- Con esto termina la serie U-10 (Ciudad, Sector, TipoEmpresa).
- Sin prueba manual en navegador.
