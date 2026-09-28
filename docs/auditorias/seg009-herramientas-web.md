# SEG-009 — Herramientas administrativas web solo en local

Fecha: 2026-09-28  
Framework verificado: Laravel `v12.69.2` en `composer.lock`.

## Resultado

Las siguientes herramientas quedan disponibles únicamente cuando
`APP_ENV=local`:

- configuración de base de datos y escritura de `.env`;
- configuración de correo y escritura de `.env`;
- configuración y ejecución de backups hacia otra base de datos;
- ejecución web de seeders;
- consulta y ejecución web de migraciones.

No existe una variable independiente que permita reactivarlas en producción.
`config/admin_tools.php` calcula `enabled` exclusivamente mediante
`env('APP_ENV') === 'local'`. En despliegues con configuración cacheada se debe
regenerar la caché después de establecer el entorno, como con cualquier cambio de
configuración de Laravel.

## Implementación

- `EnsureAdminToolsEnabled` responde 404 antes de entrar a cualquier acción cuando
  `config('admin_tools.enabled')` es falso.
- Los cinco controladores implementan el contrato `HasMiddleware` de Laravel 12 y
  aplican el middleware a todas sus acciones, sin modificar `routes/web.php`.
- `SeederController` conserva `auth` y `verified`, y `MigrationController` conserva
  `auth`, ahora dentro de la declaración estática requerida por `HasMiddleware`.
- La barra lateral oculta los cinco enlaces. Las acciones rápidas del dashboard no
  muestran seeders ni migraciones cuando la función está desactivada.
- La funcionalidad interna no fue eliminada ni modificada; con la configuración en
  `true` los índices vuelven a estar disponibles.

## Pruebas añadidas

`tests/Feature/AdminWebToolsTest.php` cubre:

1. Respuesta 404 para los cinco índices cuando la función está desactivada.
2. Respuesta 404 para creación de configuraciones de BD, correo y backup, ejecución
   de backup, seeder y migración.
3. Ausencia de escrituras en las tres tablas de configuración y ausencia de cambios
   en el registro de backup.
4. Expectativas negativas sobre `Artisan::call()` y `BackupService::executeBackup()`:
   las pruebas no ejecutan seeders, migraciones ni backups reales.
5. Ocultamiento y reaparición de los cinco enlaces del menú según la configuración.
6. Respuesta 200 para los cinco índices con `config('admin_tools.enabled', true)`;
   `migrate:status` se sustituye por un mock.

## Verificación ejecutada

- `git diff --check`: correcto.
- `php --version`: no ejecutado correctamente; PowerShell informó que `php` no está
  disponible en `PATH`.
- `php artisan test`: no pudo ejecutarse por la misma ausencia de PHP.
- `composer validate`: no pudo ejecutarse porque Composer tampoco está disponible
  en `PATH`.
- Docker no sirvió como alternativa: la consulta al daemon no respondió y se
  interrumpió sin ejecutar comandos en contenedores.

Por estas limitaciones del entorno, las pruebas quedan implementadas pero su
resultado debe confirmarse en una máquina con PHP/Composer disponibles antes del
merge.
