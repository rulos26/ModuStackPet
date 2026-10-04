---
agente: cursor
estado: terminado
rama: ia/cursor/corregir-hallazgos-039b
archivos: [routes/web.php, tests/Feature/Seg039bRoutesHardeningTest.php, docs/auditorias/seg039b-ampliacion-rutas.md]
---

# URGENTE: revertir eliminacion de rutas de dashboard activas (043 rompio el login)

La tarea 043 elimino las rutas superadmin.dashboard, admin.dashboard,
cliente.dashboard y paseador.dashboard de routes/web.php, pensando que
eran dashboards legacy duplicados.

ERROR: estas rutas NO son legacy. Son las rutas activas y centrales de
TODO el sistema de login (ver app/Http/Responses/RoleRedirect.php, que
las usa las 4; mas de 20 referencias en controladores y vistas). Al
eliminarlas, cualquier usuario que inicia sesion ahora mismo rompe con
"Route not found" al ser redirigido a su dashboard.

## Qué hacer
1. Restaura las 4 rutas de dashboard tal como estaban en origin/main
   (antes de esta rama), CON su proteccion auth+verified donde ya la
   tenian (el grupo de superadmin.dashboard protegido).
2. Si existia una version INSEGURA duplicada de alguna de estas 4 rutas
   (sin auth) ademas de la version protegida, ESA duplicada si se
   elimina - pero confirma con grep que no quede ninguna referencia a
   un nombre de ruta distinto antes de borrar nada.
3. Antes de dar esto por terminado, ejecuta este comando y confirma que
   sale VACIO (ninguna referencia rota):
   grep -rn "route('superadmin.dashboard')\|route('admin.dashboard')\|route('cliente.dashboard')\|route('paseador.dashboard')" app/ resources/views/
   luego confirma que routes/web.php SI define esos 4 nombres de ruta:
   grep -n "name('superadmin.dashboard')\|name('admin.dashboard')\|name('cliente.dashboard')\|name('paseador.dashboard')" routes/web.php
4. Los cambios de notificaciones/leidas (auth+verified) SI eran
   correctos, no los toques, consérvalos.
5. Ejecuta la suite completa.
6. IMPORTANTE: antes de dar por terminada CUALQUIER tarea que elimine
   rutas o archivos, usa grep para confirmar que nada mas del codigo los
   referencia. Esto aplica a todas tus tareas futuras.

## Entregable
Actualiza docs/auditorias/seg039b-ampliacion-rutas.md explicando el
error, la correccion, y el resultado del grep de verificacion.

## Restricciones
- No toques .env ni dependencias.
- No des la tarea por terminada sin que el grep del paso 3 salga vacio.
- Al terminar, vuelve con git switch --detach origin/main.

## Handoff
- Agente y fecha: Cursor, 2026-10-03
- Qué se hizo: Restauradas las 4 rutas de dashboard con nombre literal y auth (`superadmin` + verified + role). Admin vuelve a `login_Admin`. Eliminados los `name('dashboard')` duplicados en prefijos. Conservado auth+verified en notificaciones. No se restauró `/clientes/dashboard` ni `login_*` sin auth. Actualizado `seg039b-ampliacion-rutas.md` con la corrección 043b.
- Archivos modificados: `routes/web.php`, `tests/Feature/Seg039bRoutesHardeningTest.php`, `docs/auditorias/seg039b-ampliacion-rutas.md`, esta tarea.
- Cómo probarlo: `php artisan route:list --name=dashboard`; login Admin/Cliente/etc.; `php artisan test`.
- Pendientes y riesgos: lección — no eliminar rutas sin grep de `route('…')` y de `name('…')` (incl. nombres compuestos por prefijo).
- Preguntas para el humano: ninguna.
