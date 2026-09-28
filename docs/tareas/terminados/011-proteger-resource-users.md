---
agente: codex
estado: terminado
rama: ia/codex/proteger-resource-users
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

## Handoff
- Agente y fecha: Codex, 2026-09-28.
- Qué se hizo: se añadieron pruebas de acceso para las siete rutas `users.*`; el commit rojo registró 28 fallos y 2 éxitos. Se protegió el resource con `auth`, `verified` y `role:Superadmin`. Se documentaron el duplicado con `superadmin.usuarios.*` y el `AuthServiceProvider` no registrado.
- Archivos modificados: `routes/web.php`, `tests/Feature/UsersResourceAccessTest.php`, `docs/auditorias/seg011-resource-users.md` y esta ficha.
- Cómo probarlo: `php artisan test` (110 pruebas, 252 aserciones, todas pasan); `composer validate` (`composer.json` válido); `php artisan route:list --path=users -v` para comprobar los tres middlewares en `users.*`.
- Pendientes y riesgos: `users.*` y `superadmin.usuarios.*` son duplicados activos; se propone migrar referencias y retirar el resource raíz en otra tarea. `UserController@store` contiene un defecto preexistente de nombre de tabla (`paseadors`) que quedó visible durante la prueba roja y no se corrigió por alcance. `AuthServiceProvider` sigue sin registrarse; hoy su única policy se descubre por convención, pero futuras definiciones allí no se aplicarían.
- Preguntas para el humano: ¿se abre una tarea separada para consolidar las rutas en `superadmin.usuarios.*` y otra para registrar de forma controlada `AuthServiceProvider`? ¿Se corrige también el nombre de tabla usado por el modelo `Paseador`?
