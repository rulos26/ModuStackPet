---
agente: codex
estado: terminado
rama: ia/codex/pruebas-render-por-rol
archivos: [tests/Feature/]
---

# Red de seguridad antes de migrar el frontend

Las 313 pruebas son de logica, ninguna verifica que las vistas
rendericen. Antes de migrar a AdminLTE 4 necesitamos detectar roturas.

## Qué hacer
1. Para cada rol (Superadmin, Admin, Cliente, Paseador), una prueba que
   inicie sesion (usuario verificado) y confirme respuesta 200 en su
   dashboard y en 2-3 vistas representativas (listado, formulario,
   ficha de mascota para Cliente).
2. Pruebas de las vistas de auth: login, registro, forgot-password y
   reset-password devuelven 200 para invitados.
3. Asercion minima sobre contenido (ej. el nombre del usuario o un
   titulo), NO sobre clases CSS ni estructura HTML: el markup va a
   cambiar y las pruebas no deben romperse por eso.
4. Si una vista da 500 hoy, documentalo sin corregirlo.

## Entregable
docs/auditorias/seg046-pruebas-render.md.

## Restricciones
- Solo tests/. No modifiques app/ ni resources/.
- Al terminar, vuelve con git switch --detach origin/main.

## Handoff
- Agente y fecha: Codex, 2026-10-04.
- Qué se hizo: se añadieron pruebas de renderizado para dashboards y vistas representativas de Superadmin, Admin, Cliente y Paseador, además de las cuatro vistas públicas de autenticación. Se documentó el 500 preexistente de `admin.users.create` sin modificar producción.
- Archivos modificados: `tests/Feature/FrontendViewRenderTest.php`, `docs/auditorias/seg046-pruebas-render.md` y esta ficha movida a `terminados/`.
- Cómo probarlo: `php artisan test tests/Feature/FrontendViewRenderTest.php --compact`; suite completa con `php artisan test`; validar Composer con `composer validate --no-check-publish`.
- Pendientes y riesgos: `GET /admin/users/create` sigue respondiendo 500 por la variable `$user` ausente; las pruebas no ejecutan JavaScript ni sustituyen una revisión visual en navegador.
- Preguntas para el humano: ¿se abre una tarea separada para corregir el formulario de creación de Admin antes de migrar a AdminLTE 4?
