---
agente: codex
estado: pendiente
rama:
archivos: [routes/web.php, tests/Feature/UsersResourceAccessTest.php, docs/auditorias/]
---

# Proteger Route::resource('users') (sin ningún middleware)

Según docs/auditorias/seg010-escalada-y-kernel.md (parte C), `Route::resource('users', ...)`
en routes/web.php no tiene middleware: podría ser accesible incluso sin iniciar sesión.

## Parte A: pruebas que demuestran el hueco (commit que FALLE)
Crea `tests/Feature/UsersResourceAccessTest.php`:
- Toma los roles de los seeders (no los inventes).
- Invitado sin sesión: todas las rutas del resource redirigen a login.
- Admin, Cliente y Paseador: index, show, create, edit → 403;
  store, update, destroy → 403 y la BD NO cambia.
- Superadmin: index y show NO devuelven 403.
- Nunca ejecutes store/update/destroy como Superadmin.
Haz commit con las pruebas fallando y anota cuántas fallan.

## Parte B: corrección
- Aplica `auth`, `verified` y `role:Superadmin` a esas rutas.
- Investiga si `users.*` duplica las rutas de `superadmin/usuarios`.
  Si es un duplicado, NO lo elimines: proponlo en el Handoff.
- No modifiques controladores.

## Parte C (solo reportar): AuthServiceProvider
No está registrado en bootstrap/providers.php. Lista todo lo que define
(Gate::define, policies, before/after) y explica qué dejó de aplicarse y el
riesgo de registrarlo ahora. NO lo registres.

## Entregable
`docs/auditorias/seg011-resource-users.md`: pruebas que fallaban, corrección,
análisis de duplicado y el informe de la parte C.

## Restricciones
- Ejecuta `php artisan test`: las 80 actuales y las nuevas deben pasar.
  Si no puedes ejecutarlas, NO cierres la tarea (AGENTS.md).
- No toques `.env`, dependencias ni controladores.
- Al terminar, vuelve con `git switch --detach origin/main`.
