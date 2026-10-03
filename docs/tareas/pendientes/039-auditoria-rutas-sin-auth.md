---
agente: cursor
estado: pendiente
rama:
archivos: [docs/auditorias/]
---

# Auditoría de rutas sin autenticación (SOLO LECTURA)

Hallazgo de la tarea 034b: /ciudades/* no tenía middleware auth (ya
corregido). Claude reportó que /departamentos, /barrios, /razas,
/tipo-documentos y "otros" catálogos tienen el mismo patrón. Esta tarea
mapea el alcance completo antes de corregir nada.

## Qué hacer
1. Revisa routes/web.php completo: para cada grupo de rutas (no solo los
   ya mencionados), verifica si tiene middleware auth aplicado, directa o
   heredada de un grupo padre.
2. Para cada grupo SIN auth, clasifica:
   - Qué datos expone (lectura) y qué permite modificar (escritura:
     create/update/delete/toggle-status).
   - Severidad: crítica si permite escritura sin autenticación, alta si
     solo permite lectura de datos sensibles, media si son catálogos
     públicos sin dato sensible.
3. Para cada uno, verifica también si el controlador tiene el mismo tipo
   de bugs que encontró la 034b (columnas de clave primaria incorrectas,
   nombres de parámetro de ruta que no coinciden), sin corregir nada,
   solo reportando.
4. Revisa si ibex/crud-generator (el paquete que genera estos
   controladores, visto en composer.json) tiene un patrón de plantilla
   que explique por qué no incluye auth por defecto.

## Entregable
docs/auditorias/seg039-rutas-sin-auth.md: tabla completa
(grupo de rutas | severidad | qué expone | bugs de controlador similares
a 034b | prioridad de corrección), ordenada por severidad.

## Restricciones
- SOLO LECTURA: no modifiques ningún código, solo el entregable.
- Al terminar, vuelve con git switch --detach origin/main.
