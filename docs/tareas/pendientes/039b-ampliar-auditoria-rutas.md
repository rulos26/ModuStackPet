---
agente: cursor
estado: pendiente
rama:
archivos: [docs/auditorias/]
---

# Ampliar auditoría de rutas sin autenticación (SOLO LECTURA)

Continuacion de seg039-rutas-sin-auth.md, que menciono estos pendientes
sin detallarlos: /pdf*, las APIs de barrios, notificaciones/leidas, y
rutas duplicadas.

## Qué hacer
1. Para cada ruta /pdf* (busca todas en routes/web.php, no solo las ya
   mencionadas): que datos expone el PDF generado, tiene auth, que
   severidad si no la tiene.
2. Para las APIs de barrios (ej. barrios-api o similar, revisa el patron
   visto en ciudades-api): mismo analisis.
3. Para notificaciones/leidas: que hace esa ruta, expone datos de otros
   usuarios, tiene auth.
4. Rutas duplicadas: ademas de users.*/superadmin.usuarios.* (ya resuelto
   en tarea 020), busca si hay otros pares de rutas que hagan lo mismo
   con nombres distintos.
5. Mismo formato que seg039: tabla con severidad, que expone, prioridad.

## Entregable
docs/auditorias/seg039b-ampliacion-rutas.md.

## Restricciones
- SOLO LECTURA: no modifiques ningun codigo.
- Al terminar, vuelve con git switch --detach origin/main.
