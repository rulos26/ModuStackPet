---
agente: claude
estado: en-curso
rama: ia/claude/unificar-rutas-usuarios
archivos: [routes/web.php, resources/views/, tests/Feature/]
---

# Unificar rutas duplicadas users.* y superadmin.usuarios.*

Según docs/auditorias/seg011-resource-users.md, ambas familias de rutas
exponen las mismas 7 acciones de UserController, ya con la misma protección
(auth, verified, role:Superadmin).

## Qué hacer
1. Elige `/superadmin/usuarios` (superadmin.usuarios.*) como ruta canónica,
   según lo propuesto en seg011.
2. Busca TODAS las referencias a users.* (vistas, redirecciones, enlaces,
   pruebas) y migralas a superadmin.usuarios.*.
3. Pruebas primero (commit que falle si es posible, o ajusta las existentes
   documentando el motivo): confirma que no queda ninguna referencia rota.
4. Retira el resource duplicado `Route::resource('users', ...)` de
   routes/web.php.
5. Ejecuta toda la suite: las 144 deben seguir pasando (ajustando las que
   referenciaban users.* si hace falta, explicándolo).

## Entregable
`docs/auditorias/seg020-unificar-rutas-usuarios.md`.

## Restricciones
- No toques UserController ni Policies. Solo rutas, vistas y pruebas.
- No toques `.env` ni dependencias.
- Al terminar, vuelve con `git switch --detach origin/main`.
