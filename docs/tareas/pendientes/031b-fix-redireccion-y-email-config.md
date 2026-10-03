---
agente: claude
estado: pendiente
rama: ia/claude/fortify-auth
archivos: [app/, database/]
---

# Ajuste a la 031: redirección tras verificar correo + limpiar email_configs corrupta

Hallazgos de la prueba manual en navegador de la tarea 031 (Fortify auth),
en la misma rama ia/claude/fortify-auth (no crees una rama nueva).

## Hallazgo 1: redirección incorrecta tras verificar correo
Después de verificar el correo (/email/verify/{id}/{hash}), un usuario con
rol Cliente es redirigido a /superadmin/dashboard en vez de su dashboard
correcto, y recibe 403 "USER DOES NOT HAVE THE RIGHT ROLES". El registro
SÍ redirige correctamente (usa RoleRedirect/CustomRegisterResponse); la
verificación de correo no usa esa misma lógica.

### Qué hacer
1. Prueba primero (commit que falle): un usuario Cliente que verifica su
   correo debe terminar en /clientes/dashboard (o la ruta correcta según
   RoleRedirect), no en /superadmin/dashboard.
2. Busca qué response/acción maneja la redirección post-verificación en
   Fortify (probablemente necesita su propia implementación, similar a
   CustomLoginResponse/CustomRegisterResponse) y aplícala.
3. Verifica que esto no rompa el flujo de registro ni de login existentes.

## Hallazgo 2: fila corrupta en email_configs
Hay 1 fila activa en la tabla email_configs cuyo campo cifrado no se puede
descifrar con la APP_KEY actual (log: "Error al cargar configuración de
Email desde tabla, usando .env {"error":"The payload is invalid."}"). El
sistema cae correctamente a .env como respaldo, pero genera warnings en
cada petición.

### Qué hacer
1. Investiga el origen probable (busca si EmailConfigSeeder la creó antes
   de la tarea 012 de cifrado, o si quedó de una APP_KEY anterior).
2. Desactiva (is_active = false) o elimina esa fila corrupta específica
   desde una migración de datos nueva (no borres el seeder ni la tabla).
3. Confirma que el warning ya no aparece en el log tras una petición normal.

## Entregable
Añade una sección a docs/auditorias/seg031-fortify-auth.md documentando
ambas correcciones y su verificación.

## Restricciones
- No toques .env ni dependencias.
- Las 164 pruebas existentes deben seguir pasando, más las nuevas.
- Al terminar, vuelve con git switch --detach origin/main.
