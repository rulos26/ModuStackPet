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

## SEG-009b — Corrección del error fatal (nunca se habían ejecutado estas pruebas)

Fecha: 2026-09-28
Agente: claude (rama `ia/claude/corregir-009`, creada desde
`origin/ia/codex/desactivar-herramientas-web-produccion` e integrada con
`origin/main` mediante `merge` para incluir las tareas 008 y 010)

### Causa del error

Los 5 controladores de la tarea de Codex (`MigrationController`,
`SeederController`, `Superadmin\BackupConfigController`,
`Superadmin\DatabaseConfigController`, `Superadmin\EmailConfigController`)
implementaban `Illuminate\Routing\Controllers\HasMiddleware`, que exige un
método **estático** `public static function middleware(): array`.

El controlador base del proyecto (`app/Http/Controllers/Controller.php`)
extiende `Illuminate\Routing\Controller`, que ya declara un método de
**instancia** `public function middleware($middleware, array $options = [])`
(el patrón "legado" para registrar middleware desde el constructor, con
`$this->middleware(...)`). PHP no permite que una clase hija declare como
`static` un método que la clase padre declaró como no estático:

```
Cannot make non static method Illuminate\Routing\Controller::middleware() static
```

Esto tumbaba con un error fatal (HTTP 500) cualquier petición a esas
rutas, incluidas las que ejecutaba `tests/Feature/AdminWebToolsTest.php`,
así que ese archivo nunca había pasado ni fallado de verdad (confirmado
también arriba, en "Verificación ejecutada": Codex no pudo correr PHP en
su entorno): la suite completa no podía ni siquiera cargar esos
controladores.

`HasMiddleware` sí es compatible con controladores que extienden
directamente de una clase sin el método de instancia `middleware()` (como
los controladores nuevos de Laravel 11/12 que no usan `Illuminate\Routing\Controller`
como base), pero no con la base de este proyecto.

### Corrección

En los 5 controladores:
- Se quitó `implements HasMiddleware` y el `use Illuminate\Routing\Controllers\HasMiddleware;`.
- Se quitó el método estático `middleware(): array`.
- Se añadió (o se amplió, en `BackupConfigController`, que ya tenía
  constructor para `BackupService`) un `__construct()` que llama a
  `$this->middleware(...)` con la misma lista de middleware que
  declaraba el método estático:
  - `MigrationController`: `['auth', EnsureAdminToolsEnabled::class]`.
  - `SeederController`: `['auth', 'verified', EnsureAdminToolsEnabled::class]`.
  - `Superadmin\BackupConfigController`, `Superadmin\DatabaseConfigController`,
    `Superadmin\EmailConfigController`: `EnsureAdminToolsEnabled::class`.

No se tocó `app/Http/Controllers/Controller.php` (el controlador base),
ni `routes/web.php`, ni `bootstrap/app.php`, tal como exige la
restricción de esta tarea.

### Verificación del punto 3: ¿las pruebas de Codex verifican lo que dicen?

Se revisó `tests/Feature/AdminWebToolsTest.php` línea por línea: cubre
los 5 índices (`database-configs`, `email-configs`, `backup-configs`,
`seeders`, `migrations`) con `admin_tools.enabled=false` esperando 404, las
4 acciones de escritura/ejecución (`database-configs.store`,
`email-configs.store`, `backup-configs.store`, `backup-configs.execute`,
`seeders.execute`, `migrations.execute`) esperando 404 y sin efectos
(usa `Artisan::shouldReceive('call')->never()` y un mock de
`BackupService` que no debe recibir `executeBackup`, más conteos de filas
antes/después), la visibilidad del menú lateral según la config, y que
Superadmin sí puede usarlas cuando `admin_tools.enabled=true`. Está bien
planteada: no había que corregir ninguna aserción.

Con autorización explícita del humano (el clasificador de seguridad del
modo automático bloquea por defecto cualquier edición que "debilite"
código de seguridad, aunque sea temporal y para verificación), se quitó
momentáneamente la línea `$this->middleware(EnsureAdminToolsEnabled::class);`
de `Superadmin\DatabaseConfigController` y se corrió
`php artisan test --filter=AdminWebToolsTest`:

```
Tests:    2 failed, 10 passed (24 assertions)

FAILED > admin tool indexes are hidden when disabled with data set #0
  Expected response status code [404] but received 200.
FAILED > disabled admin tool actions do not write or execute anything
  Expected response status code [404] but received 302.
```

Fallaron exactamente las 2 pruebas que dependen de
`DatabaseConfigController` (el dataset #0 del índice es
`superadmin.database-configs.index`, y la prueba de acciones destructivas
falla en su primer `post` a `database-configs.store`); las otras 10, que
no dependen de ese controlador, siguieron pasando. Esto confirma que las
pruebas realmente detectan la ausencia del middleware y no son
tautológicas. Se restauró la línea inmediatamente; `git diff` no mostró
diferencias tras restaurarla.

### Resultado final

```
php artisan test
Tests:    80 passed (191 assertions)
```

80 = 68 de antes de esta tarea (tareas 008 y 010) + 12 de
`AdminWebToolsTest` (que antes no podían ejecutarse por el error fatal).

`composer validate`: válido. No se modificaron dependencias.

### Restricciones respetadas

- No se tocó `routes/web.php`, `bootstrap/app.php`, `.env` ni dependencias.
- No se debilitó ninguna prueba de Codex: `AdminWebToolsTest.php` se dejó
  intacto, ya estaba bien planteado.
- La edición temporal del punto 3 se hizo con autorización explícita del
  humano, se restauró de inmediato y no quedó en ningún commit (`git diff`
  vacío tras restaurarla).
