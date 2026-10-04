---
agente: cursor
estado: en-curso
rama: ia/cursor/corregir-hallazgos-039b
archivos: [routes/web.php, tests/Feature/DashboardRoleAccessTest.php, docs/auditorias/seg039b-ampliacion-rutas.md]
---

# admin.dashboard, cliente.dashboard y paseador.dashboard sin verificacion de rol

En la correccion 043b, estas 3 rutas quedaron solo con middleware auth,
sin verified ni role. Cualquier usuario autenticado de cualquier rol
puede acceder a los 3 dashboards (un Cliente puede ver /admin/dashboard,
etc.).

## Qué hacer
1. Pruebas primero (commit que FALLE): un Cliente no puede acceder a
   /admin/dashboard ni /paseador/dashboard (403); un Paseador no puede
   acceder a /admin/dashboard ni /cliente/dashboard; etc. Cada rol solo
   accede a su propio dashboard.
2. Aplica role:Admin a admin.dashboard, role:Cliente a cliente.dashboard,
   role:Paseador a paseador.dashboard. Agrega verified tambien, igual
   que ya tiene superadmin.dashboard.
3. Confirma que esto no rompe RoleRedirect (cada usuario sigue llegando
   a SU PROPIO dashboard correcto tras login/registro/verificacion).
4. Ejecuta la suite completa.

## Entregable
Actualiza docs/auditorias/seg039b-ampliacion-rutas.md con esta correccion
final.

## Restricciones
- No toques .env ni dependencias.
- Al terminar, vuelve con git switch --detach origin/main.
