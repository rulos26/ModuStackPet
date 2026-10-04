---
agente: cursor
estado: terminado
rama: ia/cursor/corregir-hallazgos-039b
archivos: [routes/web.php, tests/Feature/Seg039bRoutesHardeningTest.php, docs/auditorias/seg039b-ampliacion-rutas.md]
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

## Handoff
- Agente y fecha: Cursor, 2026-10-03
- Qué se hizo: Pruebas primero (`Seg039bRoutesHardeningTest`); `POST notificaciones/leidas` con `auth+verified` (sin IDOR entre usuarios); eliminados dashboards legacy (`/clientes/dashboard`, `login_Superadmin`/`login_Admin` sueltos, grupo superadmin intermedio sin rol); barrios JSON documentados como públicos; `empresas.pdf` confirmado OK tras 042. Actualizado `seg039b-ampliacion-rutas.md`.
- Archivos modificados: `routes/web.php`, `tests/Feature/Seg039bRoutesHardeningTest.php`, `docs/auditorias/seg039b-ampliacion-rutas.md`, esta tarea.
- Cómo probarlo: `php artisan test --filter=Seg039bRoutesHardeningTest`; `php artisan route:list --path=dashboard`; `composer validate`.
- Pendientes y riesgos: `ciudades-api` y `temp.index` siguen públicos (P2). Mensajes de excepción en APIs barrios (P3). Métodos `login_Cliente`/`login_Paseador` siguen en uso bajo auth.
- Preguntas para el humano: ¿Conviene exigir `auth` también en las APIs de barrios aunque el catálogo sea geográfico, o mantenerlas públicas?
