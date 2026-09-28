---
agente: claude
estado: terminado
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

## Handoff
- Agente y fecha: claude, 2026-09-28.
- Qué se hizo: se creó `tests/Feature/SuperadminAccessTest.php` (commit 1,
  15 fallaban/9 pasaban) demostrando que el grupo `/superadmin` solo
  exigía `auth`+`verified`. Corrección (commit 2): se registró el alias de
  middleware `role` (spatie/laravel-permission) en `bootstrap/app.php`
  —no estaba registrado porque `app/Http/Kernel.php` es código muerto
  desde la migración a la estructura sin Kernel de Laravel 11/12— y se
  añadió `role:Superadmin` al grupo en `routes/web.php`. Detalle completo,
  incluida la lista de otros grupos/rutas sin comprobación de rol, en
  `docs/auditorias/seg001-correccion.md`.
- Archivos modificados: `bootstrap/app.php`, `routes/web.php`,
  `tests/Feature/SuperadminAccessTest.php` (nuevo).
- Cómo probarlo: `php artisan test` → 56 passed (128 assertions), incluye
  las 32 pruebas previas sin cambios y las 24 nuevas.
  `composer validate` → válido.
- Pendientes y riesgos: se detectaron varios grupos de rutas
  administrativas adicionales sin comprobación de rol (algunos incluso
  sin `auth`), y una vía de escalada de privilegios directa en
  `/usuarios/roles` (cualquier usuario autenticado puede asignarse el rol
  Superadmin). Todo documentado en `docs/auditorias/seg001-correccion.md`,
  sección "Además". No se corrigió nada de eso: está fuera del alcance de
  esta tarea (`archivos:` solo cubría el grupo `/superadmin`).
  También se detectaron 3 definiciones duplicadas de la ruta
  `GET /superadmin/dashboard` (líneas 121, 148 y 313 de `routes/web.php`);
  no representan un riesgo activo porque Laravel indexa las rutas por
  método+URI y la última definición (la protegida) sobrescribe a las
  otras dos, que quedan inalcanzables. Se dejaron intactas por estar
  fuera del alcance ("no modifiques... otras rutas").
- Preguntas para el humano: ¿se autoriza una tarea nueva para blindar
  `/usuarios/roles` (la escalada de privilegios es el hallazgo más
  urgente de la lista) y los demás grupos administrativos listados en
  "Además"? ¿Se autoriza limpiar las 2 rutas duplicadas/muertas de
  `superadmin.dashboard`, aunque no sean un riesgo de seguridad activo?
