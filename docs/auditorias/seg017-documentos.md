# SEG-017: validación y aprobación de documentos de mascotas

Fecha: 2026-09-28  
Agente: Codex  
Rama: `ia/codex/documentos-autoaprobacion`

## Pruebas rojas

El commit `c176b99d` añadió tres comprobaciones antes de la corrección. El
resultado fue **3 fallos y 5 pruebas aprobadas**:

1. Un Cliente subía un PDF válido y el registro terminaba con estado
   `aprobado`, el propio Cliente en `usuario_aprobo_id` y fecha de aprobación.
2. Una excepción simulada con un secreto en el mensaje se copiaba tanto al log
   como al mensaje de sesión mostrado al usuario.
3. La edición denegada de un documento ajeno aumentaba el nivel de transacción
   de 1 a 2, porque el retorno ocurría después de `DB::beginTransaction()`.

## Separación entre validación automática y aprobación

Se reutilizó el campo existente `validacion_automatica`; no fue necesaria una
migración nueva.

Al subir un documento que supera las validaciones:

- `estado` queda en `pendiente`.
- `validacion_automatica` queda en `true`.
- `usuario_aprobo_id` y `fecha_aprobacion` quedan en `null`.
- Solo los endpoints `aprobar` y `rechazar`, restringidos a Admin o
  Superadmin, cambian la decisión humana.

La misma regla se aplica al reemplazar el archivo durante una edición: superar
la validación automática devuelve el documento a pendiente y limpia cualquier
aprobador previo.

Las vistas de listado, detalle y carga muestran por separado el estado de
aprobación y la etiqueta “Validación automática superada”.

## Transacciones

Las verificaciones de propiedad en `update` y `destroy` ahora ocurren antes de
abrir una transacción. Ya no existe una salida anticipada entre
`DB::beginTransaction()` y su correspondiente `commit` o `rollBack`.

Se retiró de `MascotaDocumentFlowsTest` el `DB::rollBack()` compensatorio de la
tarea 015. La prueba compara el nivel de transacción antes y después de denegar
la edición ajena, y confirma que permanece igual.

## Errores sanitizados

Todos los bloques `catch` de `MascotaDocumentController` dejaron de concatenar
`$e->getMessage()`:

- El usuario recibe un mensaje genérico específico de la operación.
- El log usa un texto fijo y conserva únicamente la clase de la excepción como
  contexto técnico.
- No se registran mensajes de excepción que puedan contener rutas,
  credenciales o detalles de infraestructura.

La prueba inyecta una excepción cuyo mensaje contiene un secreto y confirma que
no aparece ni en la sesión ni en el contexto registrado.

## Verificación focal

`php artisan test tests/Feature/MascotaDocumentFlowsTest.php`:
**8 pruebas y 51 aserciones aprobadas**.

