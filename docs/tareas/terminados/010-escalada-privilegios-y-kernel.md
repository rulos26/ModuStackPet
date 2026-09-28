---
agente: claude
estado: terminado
rama: ia/claude/escalada-privilegios-kernel
archivos: [routes/web.php, bootstrap/app.php, app/Http/Kernel.php, app/Http/Controllers/, app/Policies/, tests/Feature/]
---

# Escalada de privilegios en /usuarios/roles y Kernel.php sin efecto

## Parte A (URGENTE): /usuarios/roles
Según docs/auditorias/seg001-correccion.md, cualquier usuario autenticado puede
asignarse roles, incluido Superadmin.
1. Commit de pruebas que FALLEN: Admin, Cliente y Paseador no pueden asignar
   ni quitar roles (403) y su rol no cambia en la BD. Tampoco pueden
   asignárselos a sí mismos.
2. Commit de corrección: exigir rol Superadmin en la ruta Y autorización en el
   controlador (policy o Gate), como defensa en profundidad.

## Parte B: app/Http/Kernel.php no se usa (Laravel 11/12)
1. Lista TODO lo que declara Kernel.php (middleware globales, grupos, alias,
   prioridades) y compáralo con bootstrap/app.php.
2. Registra en bootstrap/app.php lo que falte y afecte a seguridad (alias,
   throttle, middleware globales). Añade pruebas cuando sea posible.
3. NO borres Kernel.php: propón su eliminación en el Handoff.

## Parte C (solo reportar)
Para cada grupo de rutas sin rol listado en "Además" de seg001-correccion.md,
propone qué rol debería exigir.

## Entregable
`docs/auditorias/seg010-escalada-y-kernel.md`: pruebas que fallaban, cambios
aplicados, tabla Kernel.php vs bootstrap/app.php y propuestas de la parte C.

## Restricciones
- En controladores, solo cambios de autorización. No toques `.env` ni dependencias.
- No desactives ni debilites pruebas. Las 56 actuales deben seguir pasando.
- Al terminar, vuelve con `git switch --detach origin/main` (AGENTS.md).

## Handoff
- Agente y fecha: claude, 2026-09-28.
- Qué se hizo:
  - **Parte A**: se creó `tests/Feature/RoleAssignmentAccessTest.php`
    (commit 1, 9/10 fallaban) confirmando empíricamente que un usuario
    Admin podía asignar Superadmin a otro usuario, y que Cliente/Paseador
    podían auto-promoverse a Superadmin. Corrección (commit 2): rol
    `Superadmin` exigido en `routes/web.php` para el grupo
    `/usuarios/roles`, más `app/Policies/UserPolicy.php` (nueva) con
    `manageRoles()`/`assignRole()`, invocadas desde
    `RoleAssignmentController` vía `$this->authorize()` como defensa en
    profundidad.
  - **Parte B**: se comparó `app/Http/Kernel.php` (huérfano, nunca se
    ejecuta) contra los defaults de Laravel 12 y `bootstrap/app.php`. Todo
    estaba ya cubierto salvo `SessionTimeout` (expiración de sesión por
    inactividad), que llevaba desactivado desde la migración. Se registró
    con `$middleware->web(append: [...])` en `bootstrap/app.php`, con
    pruebas en `tests/Feature/SessionTimeoutTest.php`. No se borró
    `Kernel.php`; se propone su eliminación (ver más abajo).
  - **Parte C**: tabla de propuestas de rol para cada grupo sin
    comprobación listado en `seg001-correccion.md`, en
    `docs/auditorias/seg010-escalada-y-kernel.md`.
  - Hallazgo adicional (solo reportado, no corregido):
    `app/Providers/AuthServiceProvider.php` tampoco está registrado en
    `bootstrap/providers.php` (mismo patrón que `Kernel.php`). Hoy no
    causa ningún fallo porque Laravel 12 descubre políticas por
    convención de nombres (así funcionan `ModulePolicy` y la nueva
    `UserPolicy`), pero cualquier lógica futura en su `boot()` (p. ej. un
    `Gate::before` global) nunca se ejecutaría. No está en `archivos:` de
    esta tarea, así que no se tocó.
- Archivos modificados: `routes/web.php`,
  `app/Http/Controllers/RoleAssignmentController.php`,
  `app/Policies/UserPolicy.php` (nuevo), `bootstrap/app.php`,
  `tests/Feature/RoleAssignmentAccessTest.php` (nuevo),
  `tests/Feature/SessionTimeoutTest.php` (nuevo).
  `app/Http/Kernel.php` no se modificó.
- Cómo probarlo: `php artisan test` → 68 passed (157 assertions): 56
  previas + 10 de `RoleAssignmentAccessTest` + 2 de `SessionTimeoutTest`.
  `composer validate` → válido. Se verificó manualmente (con
  `git stash` temporal, sin tocar el historial) que
  `SessionTimeoutTest::inactive_session_beyond_timeout_is_logged_out`
  falla sin el registro en `bootstrap/app.php`, confirmando que la prueba
  es válida.
- Pendientes y riesgos:
  - Se propone eliminar `app/Http/Kernel.php`: no se ejecuta, y su
    existencia es engañosa (alguien podría editarlo pensando que tiene
    efecto, como probablemente ocurrió con `SessionTimeout`).
  - Queda pendiente decidir si se registra `AuthServiceProvider` en
    `bootstrap/providers.php` (hallazgo de esta tarea, sin corregir por
    no estar en el alcance).
  - Ninguno de los grupos de rutas listados en la Parte C se corrigió
    (tarea de solo reporte); quedan como huecos activos hasta que se
    autorice una tarea para cada uno. El de mayor riesgo restante es
    `Route::resource('users', UserController::class)` (línea 172 de
    `routes/web.php`): CRUD completo de usuarios sin ningún middleware.
  - No se corrigió el bug funcional de `RoleAssignmentController`
    (valida el campo `roles` pero usa `$request->rol`): no es la causa
    de la escalada de privilegios y está fuera del alcance de "cambios
    de autorización".
- Preguntas para el humano: ¿se autoriza eliminar `app/Http/Kernel.php`?
  ¿Se autoriza registrar `AuthServiceProvider` en
  `bootstrap/providers.php`? ¿Cuál de los grupos de la Parte C debería
  atacarse primero — se sugiere `Route::resource('users', ...)` de la
  línea 172 por estar completamente abierto?
