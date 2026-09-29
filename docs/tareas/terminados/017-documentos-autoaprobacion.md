---
agente: codex
estado: terminado
rama: ia/codex/documentos-autoaprobacion
archivos: [app/Http/Controllers/MascotaDocumentController.php, app/Models/MascotaDocument.php, database/migrations/, resources/views/, tests/Feature/]
---

# Documentos: autoaprobación (defecto 2), transacción abierta (3) y errores expuestos (4)

Ver docs/auditorias/pruebas-mascotas-documentos.md.

## Decisión humana (opción A)
Si un documento supera la validación automática, queda `pendiente` y se marca
como "validación automática superada". Solo Admin o Superadmin lo aprueban.
Nunca se registra como aprobador a quien lo subió.

## Qué hacer
1. Pruebas primero (commit que FALLE): un Cliente que sube un documento válido
   lo deja pendiente y sin aprobador; la edición de un documento ajeno no deja
   transacciones abiertas; los errores no muestran `$e->getMessage()`.
2. Defecto 2: separa el resultado de la validación automática de la aprobación
   humana. Usa un campo existente si sirve; si no, crea una migración NUEVA
   (no modifiques migraciones existentes). Muestra el estado en las vistas.
3. Defecto 3: autoriza ANTES de `DB::beginTransaction()` y garantiza commit o
   rollBack en toda salida. Retira el `DB::rollBack()` compensatorio de tu
   prueba anterior cuando ya no sea necesario.
4. Defecto 4 solo en MascotaDocumentController: log interno sanitizado y
   mensaje genérico al usuario.

## Entregable
`docs/auditorias/seg017-documentos.md`.

## Restricciones
- No toques routes/web.php ni MascotaController (los corrige Claude en la 016).
- No toques `.env` ni dependencias. Todas las pruebas deben pasar.
- Al terminar, vuelve con `git switch --detach origin/main`.

## Handoff
- Agente y fecha: Codex, 2026-09-28.
- Qué se hizo: se añadieron primero tres pruebas rojas para autoaprobación, fuga de transacción y exposición de errores. Los documentos válidos ahora quedan pendientes, con `validacion_automatica=true` y sin aprobador; solo Admin/Superadmin aprueban. La autorización de update/destroy ocurre antes de abrir transacciones. Todos los catch del controlador muestran mensajes genéricos y registran solo la clase de excepción. Las vistas separan estado de aprobación y validación automática.
- Archivos modificados: `app/Http/Controllers/MascotaDocumentController.php`, `resources/views/mascota-documents/create.blade.php`, `resources/views/mascota-documents/index.blade.php`, `resources/views/mascota-documents/show.blade.php`, `tests/Feature/MascotaDocumentFlowsTest.php`, `docs/auditorias/seg017-documentos.md` y esta ficha.
- Cómo probarlo: `php artisan test` (136 pruebas, 371 aserciones, todas pasan); `composer validate` (`composer.json` válido). Prueba focal: `php artisan test tests/Feature/MascotaDocumentFlowsTest.php` (8 pruebas, 51 aserciones).
- Pendientes y riesgos: no fue necesaria una migración porque `validacion_automatica` ya representa el resultado automático. El almacenamiento físico de archivos no es transaccional con la BD; una excepción posterior al guardado podría dejar un archivo huérfano, riesgo preexistente fuera del alcance.
- Preguntas para el humano: ninguna para cerrar esta tarea.
