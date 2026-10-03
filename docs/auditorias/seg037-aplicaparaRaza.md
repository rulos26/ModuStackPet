# SEG-037: investigación de `aplicaParaRaza`

Fecha: 2026-10-03  
Rama: `ia/codex/aplica-para-raza`

## Comportamiento actual

`DocumentValidationService::obtenerRequisitosActivos()` carga todos los
requisitos activos y después invoca
`DocumentRequirement::aplicaParaRaza($mascota->raza)` para filtrarlos.

El método se comporta así:

- Si `aplica_razas_peligrosas` es falso, retorna `true`, por lo que el requisito
  aplica a cualquier raza.
- Si `aplica_razas_peligrosas` es verdadero, también retorna `true`. El propio
  método contiene un `TODO` que indica que falta una marca en `razas` o una
  configuración equivalente.

En consecuencia, el requisito `COMPORT` sembrado como exclusivo de razas
peligrosas se muestra para todas las mascotas, incluso cuando la mascota no
tiene raza asociada.

## Contexto disponible

- La tabla `document_requirements` solo guarda el booleano
  `aplica_razas_peligrosas`; no guarda IDs ni una relación con razas concretas.
- La tabla y el modelo `Raza` solo definen `tipo_mascota` y `nombre`; no existe
  un atributo que clasifique una raza como peligrosa.
- El catálogo auxiliar `database/sql/data_razas.sql` contiene nombres, pero no
  una clasificación. La presencia de nombres como American pit bull terrier,
  Dobermann o Rottweiler no constituye una regla de negocio autorizada.
- Las vistas permiten activar el booleano del requisito, pero no seleccionar
  razas ni administrar una clasificación.
- El código y su `TODO` existen desde el commit `f8e81673`; el historial no
  contiene una implementación anterior ni una lista de referencia eliminada.

## Decisión de producto pendiente

No es posible escribir una prueba que configure "razas específicas" con el
modelo actual: no existe ningún lugar persistente o configurable donde expresar
esa selección. Tampoco es seguro inferirla comparando nombres, porque la
clasificación depende de la jurisdicción, puede cambiar y el catálogo es editable.

Producto debe decidir uno de estos modelos antes de implementar el filtro:

1. Clasificación global: agregar a `razas` una marca administrable como
   `es_peligrosa` y hacer que el booleano del requisito consulte esa marca.
2. Selección por requisito: crear una relación muchos-a-muchos entre requisitos
   y razas para que cada requisito defina exactamente a cuáles aplica.

La segunda alternativa corresponde mejor a la frase de la tarea "configurado
para razas específicas"; la primera conserva mejor la interfaz actual de
"aplica solo para razas peligrosas". También debe definirse qué hacer cuando una
mascota no tiene raza: la opción conservadora sería no aplicar el requisito
específico hasta que exista una raza clasificable.

## Resultado

No se modificó `DocumentRequirement`, el esquema ni las pruebas: hacerlo habría
requerido inventar una clasificación o una estructura no autorizada, algo que la
tarea prohíbe expresamente cuando falta contexto de negocio.

- `php artisan test`: 196 pruebas aprobadas, 641 aserciones.
- `composer validate`: `./composer.json is valid`.

