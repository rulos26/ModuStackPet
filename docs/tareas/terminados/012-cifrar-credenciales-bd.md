---
agente: claude
estado: terminada
rama: ia/claude/cifrar-credenciales-bd
archivos: [app/Models/DatabaseConfig.php, app/Models/EmailConfig.php, app/Models/BackupConfig.php, app/Models/OAuthProvider.php, database/migrations/, database/seeders/DatabaseConfigSeeder.php, database/seeders/EmailConfigSeeder.php, tests/]
---

# SEG-004: cifrar credenciales guardadas en la BD

Ver docs/auditorias/seguridad-predespliegue.md (SEG-004).

## Qué hacer
1. Pruebas primero (commit que FALLE): al guardar una contraseña o secreto en
   DatabaseConfig, EmailConfig, BackupConfig y OAuthProvider, el valor crudo en
   la BD (consultado con DB::table) NO es el texto plano, y el modelo lo
   devuelve descifrado.
2. Corrección: cast `encrypted` en los campos secretos de esos cuatro modelos.
3. Migración nueva: cambia esas columnas a `text` (el valor cifrado supera 255
   caracteres) y cifra los valores existentes en texto plano. Debe ser
   idempotente: si un valor ya está cifrado, no volver a cifrarlo.
4. Busca accesos que se salten el modelo (DB::table, consultas crudas) a esos
   campos y repórtalos, o corrígelos si son necesarios para que funcione.
5. Documenta que estos datos dependen de APP_KEY: si se rota, usar
   APP_PREVIOUS_KEYS.

## Entregable
`docs/auditorias/seg012-cifrado-credenciales.md`.

## Restricciones
- No toques `.env`, dependencias ni rutas. No modifiques migraciones existentes.
- Todas las pruebas (110 y las nuevas) deben pasar.
- Al terminar, vuelve con `git switch --detach origin/main`.

## Handoff
- Agente y fecha: Claude, 2026-09-28.
- Qué se hizo: cast `encrypted` en `DatabaseConfig::password`,
  `EmailConfig::password`, `BackupConfig::password` y
  `OAuthProvider::client_secret`. Migración nueva
  `2025_11_06_000000_encrypt_credentials_columns.php` que cambia esas
  columnas de `string` a `text` y cifra valores existentes en texto plano de
  forma idempotente (verificado con test dedicado que corre `up()` dos
  veces). No se encontraron accesos crudos (`DB::table`, SQL directo) a
  estos campos fuera de los modelos. Detalle completo en
  `docs/auditorias/seg012-cifrado-credenciales.md`.
- Archivos modificados: `app/Models/DatabaseConfig.php`,
  `app/Models/EmailConfig.php`, `app/Models/BackupConfig.php`,
  `app/Models/OAuthProvider.php`,
  `database/migrations/2025_11_06_000000_encrypt_credentials_columns.php`
  (nuevo), `tests/Unit/EncryptedCredentialsTest.php` (nuevo),
  `tests/Unit/EncryptCredentialsMigrationTest.php` (nuevo),
  `docs/auditorias/seg012-cifrado-credenciales.md` (nuevo).
- Cómo probarlo: `php artisan test` (115 pruebas, 264 aserciones, todas en
  verde) y `composer validate`.
- Pendientes y riesgos: la migración no se probó contra datos reales de
  staging/producción (solo SQLite en memoria); tomar respaldo antes de
  aplicarla en un entorno con datos reales. `client_id` de `oauth_providers`
  se dejó sin cifrar por no ser secreto (es público en el flujo OAuth); fuera
  del alcance de SEG-004.
- Preguntas para el humano: ninguna.
