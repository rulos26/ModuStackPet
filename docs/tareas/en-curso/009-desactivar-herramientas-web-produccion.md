---
agente: codex
estado: en-curso
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
