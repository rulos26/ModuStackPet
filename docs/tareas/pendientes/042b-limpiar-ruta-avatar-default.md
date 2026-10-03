---
agente: codex
estado: pendiente
rama:
archivos: [app/Http/Controllers/, config/, resources/views/]
---

# Retirar ruta de avatar por defecto que parece contener documento real

Hallazgo de la tarea 042: la imagen por defecto usada en el PDF de mascota
tiene la ruta public/avatars/1110456003/mascotas/thanos.png. El numero
1110456003 parece una cedula colombiana real de un usuario de prueba, no
un valor generico.

## Qué hacer
1. Busca TODAS las apariciones de esa ruta especifica en el codigo (no
   solo en el PDF, revisa otras vistas tambien).
2. Reemplazala por una imagen placeholder generica del proyecto (revisa
   si ya existe algo como "default.png" o similar en public/, usalo en
   vez de inventar una ruta nueva).
3. Si existe el archivo fisico en storage/public con esa ruta, NO lo
   borres (puede ser de un usuario real en desarrollo), solo deja de
   referenciarlo por defecto en el codigo.

## Entregable
docs/auditorias/seg042b-limpiar-avatar-default.md: donde aparecia, que
se puso en su lugar.

## Restricciones
- No toques .env ni dependencias.
- Al terminar, vuelve con git switch --detach origin/main.
