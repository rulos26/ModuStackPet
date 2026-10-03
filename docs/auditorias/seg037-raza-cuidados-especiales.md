# SEG-037: cuidados especiales por raza

Fecha: 2026-10-03  
Rama: `ia/codex/raza-cuidados-especiales`

## Implementación

- Se agregaron a `razas` los campos `requiere_cuidado_especial` (booleano,
  `false` por defecto) y `cuidados_especiales` (texto nullable) mediante una
  migración nueva.
- `Raza` permite asignar ambos campos y convierte la marca a booleano.
- El formulario existente de crear/editar razas permite al Superadmin marcar
  la raza y escribir indicaciones de manejo. Si activa la marca, la descripción
  es obligatoria y admite hasta 1000 caracteres.
- No se clasificó ni modificó ninguna raza existente. La migración deja todas
  sin marca y sin indicaciones para que la configuración sea humana.

## Visualización en la ficha de mascota

En `resources/views/mascota/show.blade.php`, inmediatamente debajo del
encabezado principal de la mascota, se muestra una alerta amarilla con icono de
advertencia y el título **Cuidado especial requerido**. Debajo aparece el texto
configurado para la raza, por ejemplo "Usar bozal y mantener la correa corta".

La alerta solo se renderiza cuando la raza relacionada tiene
`requiere_cuidado_especial = true`; una mascota sin marca no muestra el bloque.
La vista ya carga la relación `raza`, por lo que no se agrega una consulta por
mascota.

## Decisión sobre `aplicaParaRaza`

Se conservó el método porque `DocumentValidationService` lo usa para filtrar
requisitos activos. El campo legado `aplica_razas_peligrosas` ahora se interpreta
como "solo razas que requieren cuidado especial":

- Si el requisito no está limitado, aplica a todas las razas.
- Si está limitado, solo aplica cuando `requiere_cuidado_especial` es verdadero.
- Si la mascota no tiene raza, el requisito limitado no aplica.

Esto elimina el retorno incondicional sin introducir listas ni clasificaciones
de peligrosidad.

## Pruebas y verificación

Se agregaron cuatro pruebas Feature, publicadas primero en rojo y luego llevadas
a verde, que cubren:

- advertencia e indicaciones para una raza marcada;
- ausencia de advertencia para una raza sin marca;
- configuración desde el panel de razas por Superadmin;
- filtrado de requisitos para raza marcada, no marcada y ausente.

Resultados finales:

- `php artisan test --filter=RazaCuidadosEspecialesTest`: 4 aprobadas,
  11 aserciones.
- `php artisan test`: 252 aprobadas, 1017 aserciones.
- `composer validate`: `./composer.json is valid`.

