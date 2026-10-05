---
agente: codex
estado: pendiente
rama:
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
