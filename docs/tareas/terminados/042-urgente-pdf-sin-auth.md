---
agente: claude
estado: terminado
rama: ia/claude/pdf-sin-auth
archivos: [routes/web.php]
---

# URGENTE: /pdf/mascota expone datos personales reales sin autenticacion

Ver docs/auditorias/seg039b-ampliacion-rutas.md. Confirmado contra datos
reales: GET /pdf/mascota (con un id de mascota) genera un PDF con email,
telefono y direccion del propietario, SIN NINGUNA autenticacion. Mismo
nivel de gravedad que el hallazgo de paths-documentos (tarea 040).

Ademas: empresas.pdf esta registrada DOS VECES en routes/web.php, con
proteccion distinta en cada registro; la que gana (linea ~231) es mas
debil, posible IDOR para cualquier usuario autenticado sin verificar
que sea su propia empresa.

## Qué hacer
1. PRIORIDAD 1: pruebas primero (commit que FALLE) confirmando que un
   invitado sin sesion puede acceder a /pdf/mascota y ver datos de un
   propietario. Luego aplica auth (y verified) a nivel de ruta, y
   verifica si ademas debe exigir que el solicitante sea el dueño de
   esa mascota o tenga rol Admin/Superadmin (revisa el patron de
   autorizacion ya usado en MascotaPolicy de la tarea 016).
2. PRIORIDAD 2: elimina la ruta duplicada de empresas.pdf, dejando
   una sola definicion con la proteccion correcta (auth + verificar que
   el usuario pertenece a esa empresa, o rol administrativo).
3. Ejecuta la suite completa.

## Entregable
docs/auditorias/seg042-pdf-sin-auth.md.

## Restricciones
- No toques .env ni dependencias.
- Al terminar, vuelve con git switch --detach origin/main.

## Handoff
- Agente y fecha: Claude, 2026-10-03
- Qué se hizo: /pdf y /pdf/mascota/{mascota?} con auth+verified y MascotaPolicy; PDF de mascota deja de ser id fijo 5 y deja de dar 500 (relación barrio inexistente); eliminada la ruta duplicada empresas.pdf.
- Archivos modificados: routes/web.php, app/Http/Controllers/PDFController.php, tests/Feature/PdfAccessTest.php, docs/auditorias/seg042-pdf-sin-auth.md
- Cómo probarlo: `php artisan test` (266 passed); abrir /pdf/mascota/{id} sin sesión (→ login) y como dueño (PDF).
- Pendientes y riesgos: la URL /pdf/mascota sin id ahora redirige al listado de mascotas; resto de P1/P2 de seg039b; sin prueba manual en navegador.
- Preguntas para el humano: ¿Algún cliente externo usaba /pdf/mascota sin id? (devolvía el PDF de la mascota 5). ¿Se retira la imagen por defecto con ruta de usuario real en public/avatars?
