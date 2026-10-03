---
agente: codex
estado: terminado
rama: ia/codex/limpiar-avatar-default
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

## Handoff
- Agente y fecha: Codex, 2026-10-03.
- Qué se hizo: se localizaron las dos referencias a la ruta con apariencia de
  documento real y se sustituyeron por `public/ruta_default_avatar.png`, que ya
  era el placeholder genérico versionado del proyecto. No se tocó ningún archivo
  de usuario.
- Archivos modificados: `app/Http/Controllers/PDFController.php`,
  `resources/views/pdf/ejemplo.blade.php` y
  `docs/auditorias/seg042b-limpiar-avatar-default.md`.
- Cómo probarlo: `php artisan test`, `composer validate` y buscar la cadena
  anterior fuera de `docs/tareas/`.
- Pendientes y riesgos: ninguno identificado. Suite completa: 258 pruebas
  aprobadas, 1047 aserciones.
- Preguntas para el humano: ninguna.
