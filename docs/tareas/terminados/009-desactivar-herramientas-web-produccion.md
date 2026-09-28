---
agente: codex
estado: terminado
rama: ia/codex/desactivar-herramientas-web-produccion
archivos: [config/admin_tools.php, app/Http/Middleware/, app/Http/Controllers/Superadmin/DatabaseConfigController.php, app/Http/Controllers/Superadmin/EmailConfigController.php, app/Http/Controllers/Superadmin/BackupConfigController.php, app/Http/Controllers/SeederController.php, app/Http/Controllers/MigrationController.php, resources/views/, tests/Feature/AdminWebToolsTest.php]
---

# Desactivar herramientas administrativas web fuera de entorno local

Decisión humana aprobada (ver docs/auditorias/seguridad-predespliegue.md,
SEG-002, SEG-003, SEG-007 y SEG-008): editor de BD/.env, editor de correo/.env,
backup a otra BD, seeders web y migraciones web SOLO funcionan con APP_ENV=local.

## Qué hacer
1. Crea `config/admin_tools.php` con `'enabled' => env('APP_ENV') === 'local'`.
   Sin variable para reactivarlo en producción.
2. Crea un middleware que devuelva 404 si `config('admin_tools.enabled')` es false.
3. Aplícalo desde los controladores (interfaz `HasMiddleware`), NO en
   routes/web.php (Claude está modificando ese archivo en la tarea 010):
   DatabaseConfigController, EmailConfigController, BackupConfigController,
   SeederController y MigrationController, en TODAS sus acciones.
4. Oculta sus enlaces del menú cuando esté desactivado.
5. Pruebas en `tests/Feature/AdminWebToolsTest.php`:
   - Desactivado: un Superadmin recibe 404 en índice y acciones de cada herramienta,
     y ninguna acción de escritura/ejecución tiene efecto.
   - Activado (config en true dentro de la prueba): un Superadmin ve los índices (200).
   - NUNCA ejecutes backup, seeders ni migraciones reales en las pruebas.

## Entregable
`docs/auditorias/seg009-herramientas-web.md`: qué se desactivó, cómo se activa en
local, pruebas y resultado de `php artisan test`.

## Restricciones
- No toques routes/web.php, bootstrap/app.php, `.env` ni dependencias.
- No modifiques `tests/Feature/SuperadminAccessTest.php`. Si alguna prueba
  existente deja de pasar, detente y repórtalo.
- No elimines la funcionalidad: solo condiciónala al entorno.
- Al terminar, vuelve con `git switch --detach origin/main` (AGENTS.md).

## Handoff

- Agente y fecha: Codex, 2026-09-28.
- Qué se hizo: se añadió `config/admin_tools.php`, un middleware fail-closed con
  respuesta 404 y su aplicación mediante `HasMiddleware` a los cinco controladores.
  También se ocultaron los enlaces y se añadieron pruebas de índices, acciones,
  efectos laterales y menú en estados desactivado/activado.
- Archivos modificados: `config/admin_tools.php`,
  `app/Http/Middleware/EnsureAdminToolsEnabled.php`, los controladores
  `DatabaseConfigController`, `EmailConfigController`, `BackupConfigController`,
  `SeederController` y `MigrationController`,
  `resources/views/superadmin/sidebar.blade.php`,
  `resources/views/superadmin/dashboard.blade.php`,
  `tests/Feature/AdminWebToolsTest.php`,
  `docs/auditorias/seg009-herramientas-web.md` y esta ficha movida a terminados.
- Cómo probarlo: ejecutar `composer validate` y `php artisan test`; opcionalmente
  ejecutar solo `php artisan test tests/Feature/AdminWebToolsTest.php`. Verificar
  manualmente que con `APP_ENV=local` aparecen y responden los cinco índices, y
  fuera de local sus URLs responden 404 y no aparecen en el menú. `git diff
  --check` pasó. No fue posible ejecutar PHP/Composer: ambos faltan del PATH;
  Docker tampoco respondió y se interrumpió sin ejecutar comandos en contenedores.
- Pendientes y riesgos: confirmar toda la suite en un entorno con PHP disponible
  antes del merge. Con configuración cacheada, regenerar la caché tras fijar el
  entorno del despliegue. No se observó el resultado de pruebas automatizadas.
- Preguntas para el humano: ninguna sobre el alcance funcional; falta resolver la
  disponibilidad de PHP/Composer en este worktree para validar la suite.
