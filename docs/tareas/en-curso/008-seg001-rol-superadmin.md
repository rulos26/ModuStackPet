---
agente: claude
estado: en-curso
rama: ia/claude/seg001-rol-superadmin
archivos: [routes/web.php, bootstrap/app.php, tests/Feature/SuperadminAccessTest.php]
---

# SEG-001: exigir el rol Superadmin en todo el grupo /superadmin

Ver docs/auditorias/seguridad-predespliegue.md (SEG-001, confirmado por el humano):
el grupo `superadmin` de routes/web.php solo aplica `auth` y `verified`.

## Commit 1: pruebas que demuestran el hueco (deben FALLAR)
Crea `tests/Feature/SuperadminAccessTest.php`:
- Toma los nombres de roles de los seeders de roles (no los inventes).
- Para cada rol que NO sea Superadmin: GET a las rutas índice del grupo
  (dashboard, usuarios, database-configs, email-configs, backup-configs,
  migrations, oauth-providers, configuraciones) debe devolver 403.
- Para esos mismos roles: POST a `backup-configs.execute`,
  `migrations.execute`, `database-configs.store` y `usuarios.store` debe
  devolver 403, y la BD no debe cambiar.
- Invitado sin sesión: redirección a login.
- Superadmin: las rutas índice GET NO devuelven 403.
- NUNCA ejecutes rutas destructivas (execute, store, destroy) como Superadmin.
Haz commit con las pruebas fallando y anota cuántas fallan.

## Commit 2: la corrección
- Añade al grupo el middleware de rol de spatie/laravel-permission para
  Superadmin. En Laravel 11/12 el alias `role` debe registrarse en
  `bootstrap/app.php`: compruébalo y regístralo si falta.
- No modifiques controladores ni otras rutas.
- Todas las pruebas (las 32 existentes y las nuevas) deben pasar.

## Además (solo reportar, no corregir)
Revisa el resto de routes/web.php y lista otros grupos o rutas administrativas
sin comprobación de rol.

## Entregable
`docs/auditorias/seg001-correccion.md`: pruebas que fallaban antes, cambio
aplicado, resultado después y la lista del punto "Además".

## Restricciones
- No toques `.env`, dependencias ni controladores.
- No desactives ni debilites pruebas existentes.
