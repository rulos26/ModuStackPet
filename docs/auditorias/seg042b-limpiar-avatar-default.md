# SEG-042b: limpieza de avatar por defecto

Fecha: 2026-10-03  
Rama: `ia/codex/limpiar-avatar-default`

## Hallazgo

La ruta con un número que aparenta ser un documento de identidad aparecía en
dos lugares de código:

- `app/Http/Controllers/PDFController.php`, como imagen alternativa del PDF de
  mascota cuando el registro no tiene avatar o su archivo no existe.
- `resources/views/pdf/ejemplo.blade.php`, como avatar fijo del PDF de ejemplo.

La búsqueda completa no encontró otras referencias fuera de la propia ficha de
tarea.

## Corrección

Ambas referencias usan ahora `public/ruta_default_avatar.png`, un placeholder
genérico que ya estaba versionado en el proyecto. No se creó ni copió ninguna
imagen nueva.

No se inspeccionó, modificó ni eliminó el archivo de la ruta anterior: puede
pertenecer a datos locales de desarrollo y queda fuera del alcance de esta
corrección.

## Verificación

- `public/ruta_default_avatar.png` existe en el repositorio.
- La ruta anterior ya no aparece en controladores, vistas ni configuración.
- `php -l app/Http/Controllers/PDFController.php` no reporta errores.
- `php artisan test`: 258 pruebas aprobadas, 1047 aserciones.
- `composer validate`: `./composer.json is valid`.
