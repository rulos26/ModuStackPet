---
agente: cursor
estado: pendiente
rama:
archivos: [routes/web.php, app/Http/Controllers/]
---

# Corregir hallazgos P1/P2 de seg039b (APIs y rutas duplicadas)

Ver docs/auditorias/seg039b-ampliacion-rutas.md, tu propia investigacion
previa. Ahora conviertela en correcciones reales, con el mismo patron
usado en 040/041/042: pruebas primero, luego el fix.

## Qué hacer
1. Barrios JSON (/barrios-engativa, /barrios-por-ciudad/{ciudadId}):
   evalua si deben requerir auth o si son datos publicos legitimos
   (catalogo geografico sin dato personal). Si decides que deben
   protegerse, pruebas primero y luego el fix. Si concluyes que son
   publicos por diseño (como ciudades-api), documentalo sin cambiar nada
   y explica por que.
2. POST /notificaciones/leidas: confirma si mezcla datos de otros
   usuarios. Si un usuario autenticado puede marcar como leida una
   notificacion ajena, es un hueco real: pruebas primero, luego fix
   (debe verificar que la notificacion pertenece al usuario autenticado).
3. Rutas duplicadas de dashboard (cliente.dashboard publico vs
   cliente/dashboard con auth, login_* legacy): determina cual es la que
   realmente usa la aplicacion hoy (revisa vistas y redirecciones) y
   elimina o protege la duplicada insegura, con pruebas primero.
4. empresas.pdf duplicada ya se corrigio en la tarea 042 - confirma que
   sigue resuelto, no la toques de nuevo.

## Entregable
Actualiza docs/auditorias/seg039b-ampliacion-rutas.md con una seccion de
correcciones aplicadas, y cuales decidiste dejar como estan con su
justificacion.

## Restricciones
- No toques .env ni dependencias.
- Si algo requiere una decision de producto que no esta clara, detente y
  pregunta en el Handoff en vez de decidir tu.
- Al terminar, vuelve con git switch --detach origin/main.
