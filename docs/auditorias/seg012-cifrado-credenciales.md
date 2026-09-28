# SEG-004 / SEG-012: cifrado de credenciales guardadas en la BD

Fecha: 2026-09-28
Agente: Claude
Rama: `ia/claude/cifrar-credenciales-bd`

## Hallazgo y prueba roja

`DatabaseConfig::password`, `EmailConfig::password`, `BackupConfig::password`
y `OAuthProvider::client_secret` se guardaban en texto plano en las tablas
`database_configs`, `email_configs`, `backup_configs` y `oauth_providers`
(columnas `string`/varchar 255). Los cuatro modelos las incluían en `$hidden`,
lo que evita que aparezcan en respuestas JSON, pero no cifra el valor
almacenado: cualquiera con acceso de lectura a la BD (dump, backup, consola
del proveedor de hosting) veía las contraseñas y el client secret OAuth sin
protección adicional.

El commit `a832ad6f` añadió `tests/Unit/EncryptedCredentialsTest.php`. Antes
de la corrección, las 4 pruebas fallaban: cada una comparaba el valor crudo
leído con `DB::table(...)->value(...)` contra el texto plano insertado y
esperaba que fueran distintos (`assertNotSame`), lo cual no se cumplía.

## Corrección

1. Cast `encrypted` en el campo secreto de cada modelo
   ([DatabaseConfig.php](../../app/Models/DatabaseConfig.php),
   [EmailConfig.php](../../app/Models/EmailConfig.php),
   [BackupConfig.php](../../app/Models/BackupConfig.php),
   [OAuthProvider.php](../../app/Models/OAuthProvider.php)). Laravel cifra el
   valor al guardar (con `Crypt`, basado en `APP_KEY`) y lo descifra
   automáticamente al leer el atributo del modelo, de forma transparente
   para el resto del código (controladores, `toConfigArray()`,
   `updateEnvFile()`, `getConfigAttribute()`, etc., que siguen accediendo a
   `->password` / `->client_secret` como antes).
2. Migración nueva
   [2025_11_06_000000_encrypt_credentials_columns.php](../../database/migrations/2025_11_06_000000_encrypt_credentials_columns.php):
   - Cambia las 4 columnas de `string` a `text`, porque un valor cifrado con
     `Crypt` supera los 255 caracteres de un varchar.
   - Cifra los valores existentes en texto plano fila por fila.
   - Es **idempotente**: antes de cifrar, intenta `Crypt::decryptString()`
     sobre el valor actual; si tiene éxito, asume que ya está cifrado y lo
     deja igual. Verificado con
     `tests/Unit/EncryptCredentialsMigrationTest.php`, que instancia la
     migración directamente, inserta una fila en texto plano, corre `up()`
     dos veces y confirma que el valor cifrado no cambia entre la primera y
     la segunda corrida.
   - `down()` revierte: descifra los valores cifrados antes de que una
     migración posterior (no incluida aquí) pudiera volver la columna a
     `string`. No se modificó ninguna migración existente.

Tras la corrección, las 4 pruebas de `EncryptedCredentialsTest` pasan, y la
suite completa (`php artisan test`) da **115 pruebas, 264 aserciones**, todas
en verde (110 previas + 5 nuevas: 4 de cifrado + 1 de idempotencia de la
migración). `composer validate` también pasa.

## Accesos que se saltan el modelo

Se buscó en `app/`, `database/`, `tests/`, `routes/` y `config/` cualquier
`DB::table`, `DB::select`, `DB::statement` o `Schema::` que leyera o
escribiera las columnas `password` de las tres tablas de configuración o
`client_id`/`client_secret` de `oauth_providers`. **No se encontró ninguno.**
Todo el código de la aplicación accede a estos campos a través de los
modelos Eloquent:

- `app/Http/Controllers/Superadmin/OAuthProviderController.php` (pruebas de
  configuración del provider, líneas ~170-297) lee `client_id`/`client_secret`
  vía atributo del modelo.
- `app/Providers/AppServiceProvider.php:128` hace
  `Config::set('mail.mailers.smtp.password', $emailConfig->password)`, también
  vía atributo.
- Los seeders `DatabaseConfigSeeder` y `EmailConfigSeeder` usan
  `Model::updateOrCreate(...)`, no SQL crudo.

No fue necesario corregir ningún acceso adicional.

## Dependencia de `APP_KEY` y rotación

Estos cuatro campos ahora dependen de `APP_KEY` para poder leerse. Si se
rota `APP_KEY`:

- Laravel seguirá pudiendo descifrar valores cifrados con la clave anterior
  **solo** si esa clave anterior se agrega a `APP_PREVIOUS_KEYS` (variable de
  entorno, formato `base64:xxx,base64:yyy`), soportada de forma nativa desde
  `config/app.php` (`'previous_keys' => array_filter(explode(',', env('APP_PREVIOUS_KEYS', '')))`).
- Sin `APP_PREVIOUS_KEYS`, tras rotar `APP_KEY` todas las contraseñas y
  secretos guardados en estas 4 tablas quedan ilegibles (el cast `encrypted`
  lanzará `DecryptException` al leer el atributo) y las conexiones que
  dependan de ellos (BD alterna, SMTP, backups, login social) dejarán de
  funcionar hasta volver a capturar las credenciales.
- Procedimiento recomendado al rotar `APP_KEY`: mover la clave vieja a
  `APP_PREVIOUS_KEYS`, desplegar, y re-guardar (no solo releer) cada
  registro de estas 4 tablas mientras ambas claves siguen disponibles, para
  que Laravel las vuelva a cifrar con la clave nueva. Después de eso puede
  retirarse la clave vieja de `APP_PREVIOUS_KEYS`.

## Restricciones respetadas

No se tocó `.env`, no se agregaron ni cambiaron dependencias, no se
modificaron rutas, y no se tocó ninguna migración existente (solo se agregó
una migración nueva).

## Pendientes y riesgos

- No se migraron datos de un entorno real (staging/producción): la migración
  se probó contra SQLite en memoria (pruebas) y su lógica es genérica para
  MySQL, pero conviene tomar un respaldo antes de aplicarla en un entorno con
  datos reales.
- El campo `client_id` de `oauth_providers` se dejó sin cifrar: no es un
  secreto (es público en el flujo OAuth, se expone en la URL de
  autorización), así que no estaba en el alcance de la tarea (SEG-004 solo
  pide contraseñas/secretos). Se documenta aquí por transparencia.
