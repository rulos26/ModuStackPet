---
agente: claude
estado: terminado
rama: ia/claude/consolidar-sector
archivos: [app/Models/Sector.php, app/Models/Sectore.php, app/Http/Controllers/, resources/views/, tests/]
---

# U-10 (parte 2 de 3): consolidar Sector/Sectore

Ver docs/auditorias/informe-unificado-y-plan.md (U-10) y
docs/auditorias/seg034-consolidar-ciudad.md como referencia del mismo
proceso ya aplicado a Ciudad/Ciudade. Sigue exactamente el mismo metodo.

## Qué hacer
1. Pruebas de caracterización primero del comportamiento actual de ambos
   modelos.
2. Determina cual modelo conservar (revisa uso real, igual que en 034).
3. Migra todos los consumidores al modelo que se conserva.
4. Mueve el modelo descartado a _borrar/modelos/.
5. REVISA DE INMEDIATO si las rutas de sectores tienen auth a nivel de
   ruta (no solo constructor). Si no la tienen, aplicala en esta misma
   tarea con pruebas primero, igual que se hizo en 034b/040/041 - no
   dejes un hueco de seguridad sin corregir como paso separado esta vez.
6. Revisa tambien si el controlador tiene bugs de columna de clave
   primaria o nombre de parametro de ruta, como se encontro en Ciudad.
   Corrigelos en la misma tarea si los hay.

## Entregable
docs/auditorias/seg035-consolidar-sector.md.

## Restricciones
- No toques TipoEmpresa/TiposEmpresa (es la 036, despues de esta).
- No toques .env ni dependencias.
- Al terminar, vuelve con git switch --detach origin/main.

## Handoff
- Agente y fecha: Claude, 2026-10-03
- Qué se hizo: Sector/Sectore consolidados en `Sector`; `Sectore` movido a `_borrar/modelos/`; findOrFail en show/edit/destroy. Las rutas ya tenían auth desde la 040.
- Archivos modificados: app/Http/Controllers/SectoreController.php, app/Models/{Sector,Empresa}.php, app/Models/Sectore.php (eliminado), tests/Feature/SectorCharacterizationTest.php, docs/auditorias/seg035-consolidar-sector.md
- Cómo probarlo: `php artisan test` (254 passed); `composer dump-autoload` si el classmap local aún lista Sectore.
- Pendientes y riesgos: borrado ahora lógico y la regla unique bloquea reutilizar nombres de sectores eliminados; sin prueba manual en navegador.
- Preguntas para el humano: ¿Ajustamos `unique:sectores,nombre` para ignorar filas eliminadas (`whereNull('deleted_at')`)?
