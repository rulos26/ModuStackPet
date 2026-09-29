# Pendiente: rotación de credenciales expuestas

Fecha: 2026-09-28

## Contexto
Las siguientes credenciales estuvieron expuestas públicamente en el
historial de git de GitHub antes de ser retiradas de `.env.example` y del
repositorio (ver commits de limpieza de septiembre de 2026):

- Contraseña de aplicación de Gmail (MAIL_PASSWORD)
- Secreto de cliente OAuth de Google (GOOGLE_CLIENT_SECRET)
- Secreto de cliente OAuth de Facebook (FACEBOOK_CLIENT_SECRET)
- Usuario y nombre de la base de datos de Hostinger (no la contraseña,
  que ya estaba vacía en el momento de la exposición)

## Decisión humana (2026-09-28)
CONGELADO. Se aplaza la rotación hasta hacer una reevaluación completa de
cómo va a funcionar el ingreso por redes sociales (login con Google y
Facebook) en el proyecto. No se ejecuta ninguna acción hasta entonces.

## Al retomar esto, hay que
1. Revocar/regenerar la contraseña de aplicación de Gmail.
2. Revocar/regenerar el secreto de cliente de Google (según cómo quede
   diseñado el login social).
3. Revocar/regenerar el secreto de cliente de Facebook (si se mantiene ese
   proveedor).
4. Actualizar el `.env` local y, cuando exista, el del servidor.
5. Confirmar que ningún valor viejo siga funcionando.
