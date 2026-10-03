---
agente: claude
estado: terminado
rama: ia/claude/consolidar-ciudad
archivos: [app/Models/Ciudad.php, app/Models/Ciudade.php, app/Http/Controllers/, app/Models/Departamento.php, resources/views/, tests/]
---

# U-10 (parte 1 de 3): consolidar Ciudad/Ciudade

Ver docs/auditorias/informe-unificado-y-plan.md (U-10, §6.1) y
docs/auditorias/seg021-kernel-y-modelos-duplicados.md para el detalle de
cuál modelo se usa dónde.

Nota de paralelismo (§6.1 del informe): este par comparte archivos con
Sector/Sectore y TipoEmpresa/TiposEmpresa (EmpresaController.php). Esta es
la PRIMERA de tres tareas secuenciales (034 -> 035 -> 036). No se lanzarán
035/036 hasta que esta termine y se fusione.

## Qué hacer
1. Pruebas de caracterización primero: antes de tocar nada, escribe
   pruebas que describan el comportamiento ACTUAL de ambos modelos
   (Ciudad y Ciudade) tal como están, para tener una red de seguridad.
2. Determina cuál es el modelo "correcto" a conservar (revisa cuál tiene
   más uso real, cuál coincide con el nombre de tabla, consulta
   seg021 si ya lo investigó).
3. Migra todos los consumidores (controladores, vistas, relaciones en
   Departamento y otros modelos) al modelo que se conserva.
4. Mueve el modelo descartado a _borrar/ (no lo elimines del todo).
5. Ejecuta la suite completa: las 169 + las nuevas deben pasar.

## Entregable
docs/auditorias/seg034-consolidar-ciudad.md: qué se conservó, qué se
movió a _borrar/, archivos migrados, resultado de pruebas.

## Restricciones
- No toques Sector/Sectore ni TipoEmpresa/TiposEmpresa (son las tareas
  035/036, después de esta).
- No toques .env ni dependencias.
- Al terminar, vuelve con git switch --detach origin/main.

## Handoff
- Agente y fecha: Claude, 2026-10-03
- Qué se hizo: Ciudad/Ciudade consolidados en `Ciudad`; consumidores migrados; `Ciudade` movido a `_borrar/modelos/`.
- Archivos modificados: app/Http/Controllers/CiudadController.php, app/Models/{Departamento,Empresa}.php, app/Models/Ciudade.php (eliminado), resources/views/user/form.blade.php, tests/Feature/CiudadCharacterizationTest.php, docs/auditorias/seg034-consolidar-ciudad.md
- Cómo probarlo: `php artisan test` (179 passed); `composer dump-autoload` si el classmap local aún lista Ciudade.
- Pendientes y riesgos: borrado de ciudades ahora es lógico; CRUD de ciudades ya estaba roto (create/edit 500) y sin `auth` — ver informe. No hice prueba manual en navegador.
- Preguntas para el humano: ¿Abrimos tareas para (a) arreglar el CRUD de ciudades y (b) proteger /ciudades con auth/rol? Estas deberían ir antes de 035/036 o en paralelo, sin tocar EmpresaController.
