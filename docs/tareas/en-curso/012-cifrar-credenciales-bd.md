---
agente: claude
estado: en-curso
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
