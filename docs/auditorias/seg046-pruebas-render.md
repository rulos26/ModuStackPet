# SEG-046 — Pruebas de renderizado por rol

Fecha: 2026-10-04  
Rama: `ia/codex/pruebas-render-por-rol`

## Cobertura añadida

Se agregó `tests/Feature/FrontendViewRenderTest.php` con cinco casos y 38 aserciones. Las comprobaciones usan respuesta HTTP y texto estable; no dependen de clases CSS ni de la estructura HTML.

- Superadmin: dashboard, listado de usuarios y formulario de creación.
- Admin: dashboard, listado, detalle y formulario de edición de usuarios.
- Cliente: dashboard, listado de mascotas, formulario de creación y ficha de una mascota propia.
- Paseador: dashboard, listado de perfil, detalle y formulario de edición propio.
- Invitado: login, registro, solicitud de recuperación y restablecimiento de contraseña.

## Hallazgo preexistente

`GET /admin/users/create` responde 500 en el estado actual de `origin/main`.

- Excepción: `Undefined variable $user`.
- Vista: `resources/views/user/form.blade.php`.
- Origen observado: `AdminController::create()` entrega `roles` y `tiposDocumento`, pero la vista parcial también espera `$user`.
- Decisión: no se corrigió ni se modificó la prueba para ocultarlo; la tarea restringe los cambios a pruebas y pide documentar las vistas que ya fallen.
- Cobertura alternativa del formulario Admin: `GET /admin/users/{user}/edit`, que sí entrega `$user` y renderiza 200.

## Verificación

- `php artisan test tests/Feature/FrontendViewRenderTest.php --compact`: 5 pruebas aprobadas, 38 aserciones.
- `php artisan test`: 318 pruebas aprobadas, 1294 aserciones.
- `composer validate --no-check-publish`: `composer.json` válido.

## Riesgos pendientes

- El formulario de creación de Admin permanece roto hasta corregir el contrato entre controlador y vista.
- Estas son pruebas de renderizado del servidor; no ejecutan JavaScript ni validan comportamiento visual en navegador.
