---
agente: codex
estado: pendiente
rama:
archivos: [docs/auditorias/]
---

# Auditoría de seguridad previa al despliegue (SOLO LECTURA)

La app aún no está en producción. Objetivo: lista priorizada de lo que debe
corregirse ANTES de publicar en un hosting compartido (Hostinger).

## Revisa
1. Editor de `.env` desde la app (DatabaseConfigEnvUpdate): quién accede,
   middleware y rutas, validación, inyección de saltos de línea o variables.
2. `SeederController`: ¿se pueden ejecutar seeders desde la web? ¿quién?
3. Tablas `database_configs` y `email_configs`: ¿guardan contraseñas en texto
   plano o cifradas? ¿quién puede verlas?
4. `CheckModuleStatus`: comportamiento ante error de BD (acceso permitido).
5. Contraseñas fijas en `database/seeders/UserSeeder.php` y `TokenSeeder.php`.
6. Rutas escritas como `/public/...` en vistas y el supuesto de que la raíz
   del sitio sea la carpeta del proyecto: ¿`.htaccess` protege `.env`,
   `storage/`, `vendor/` y `database/`?
7. Backups (`backup_configs`, `backup_logs`): dónde se guardan y si son
   accesibles desde la web.
8. `oauth_test_logs` y `social_accounts`: ¿guardan tokens?

## Entregable
`docs/auditorias/seguridad-predespliegue.md`: tabla
(ID | severidad | hallazgo | evidencia archivo:línea | corrección propuesta),
ordenada por severidad, y una lista de verificación para el despliegue.

## Restricciones
- SOLO LECTURA: no modifiques código, configuración ni dependencias.
- No leas `.env`. No ejecutes seeders ni migraciones contra ninguna BD real.
