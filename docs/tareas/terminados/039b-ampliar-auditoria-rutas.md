---
agente: cursor
estado: terminado
rama: ia/cursor/ampliar-auditoria-rutas-039b
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

## Handoff
- Agente y fecha: Cursor, 2026-10-03
- Qué se hizo: Ampliación SEG-039b en `docs/auditorias/seg039b-ampliacion-rutas.md` (PDF*, APIs barrios, notificaciones/leidas, duplicados). Revisión con `php artisan route:list --json` sobre `routes/web.php` en 11a92420.
- Archivos modificados: `docs/auditorias/seg039b-ampliacion-rutas.md`, movimiento de tarea a `terminados/`.
- Cómo probarlo: Leer el informe; opcional `php artisan route:list --path=pdf` y `--path=barrios` para contrastar middleware.
- Pendientes y riesgos: P1 sin corregir en código (PDF mascota anónimo, PDF empresa IDOR, barrios JSON públicos, POST notificaciones sin auth). `seg039-rutas-sin-auth.md` sigue solo en rama `ia/cursor/auditoria-rutas-sin-auth`.
- Preguntas para el humano: ¿Priorizar un solo PR P1 (PDF + barrios + notificaciones + quitar L231) o tareas separadas?
