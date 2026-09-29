# SEG-027 / U-02: limitar AutoClean al entorno local

Fecha: 2026-09-28  
Agente: Codex  
Rama: `ia/codex/autoclean-admin-tools`

## Problema

Las rutas de AutoClean ya estaban protegidas por autenticación, rol
`Superadmin`, estado del módulo `clean` y límites de frecuencia. Sin embargo,
`CleanController` no aplicaba `EnsureAdminToolsEnabled`, a diferencia de las
otras herramientas administrativas sensibles.

Ocultar el enlace del menú cuando `admin_tools.enabled` era `false` no impedía
el acceso directo a `superadmin/clean`: un Superadmin todavía obtenía respuesta
200 y podía ejecutar comandos de limpieza fuera del entorno local.

## Prueba roja

El commit `885f6423` añadió `superadmin.clean.index` al proveedor de casos de
`AdminWebToolsTest`.

Antes de la corrección:

- Herramientas desactivadas: se esperaba 404, pero AutoClean respondió 200.
- Herramientas activadas: AutoClean respondió 200 correctamente.
- Resultado focalizado: 1 prueba fallida y 13 correctas.

La prueba reutiliza los mismos casos que protegen configuración de base de
datos, correo, backup, migraciones y seeders.

## Corrección

El commit `46b4c1ea` modificó únicamente `CleanController`:

- Importó `App\Http\Middleware\EnsureAdminToolsEnabled`.
- Cambió el middleware del constructor de `auth` a
  `['auth', EnsureAdminToolsEnabled::class]`.

El middleware responde 404 cuando `config('admin_tools.enabled')` es falso. La
configuración solo lo habilita cuando `APP_ENV` es `local`, sin modificar rutas,
comandos disponibles ni el control adicional de rol Superadmin.

## Verificación

- `php artisan test tests/Feature/AdminWebToolsTest.php`: 14 pruebas y 36
  aserciones correctas.
- `php artisan test`: 146 pruebas y 438 aserciones correctas.
- `composer validate`: `composer.json` válido.

No se modificaron `routes/web.php` ni otros controladores.
