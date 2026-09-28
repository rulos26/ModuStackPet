---
agente: codex
estado: en-curso
rama: ia/codex/diagnosticar-pruebas
archivos: [tests/, database/factories/]
---

# Diagnosticar y corregir las pruebas que fallan

## Contexto
`php artisan test` (SQLite en memoria, configurado en phpunit.xml) da
27 fallidas, 4 advertencias y 1 pasada. Error visible: "FOREIGN KEY constraint
failed" al insertar en `module_logs` con `user_id = 0`. Además, 4 pruebas pasan
solo si existe `.env` y generan advertencias sin él.

## Qué hacer
1. Agrupa los fallos por causa raíz (no prueba por prueba).
2. Clasifica cada causa: error de la prueba o factory, error del código de la
   aplicación, o diferencia entre SQLite y MySQL.
3. Corrige las causas que estén en `tests/` o `database/factories/`.
4. Para causas en `app/`, migraciones o configuración: NO las cambies.
   Documenta la corrección propuesta con archivo, línea y motivo.
5. Identifica qué variable o configuración necesitan las 4 pruebas con
   advertencias y propón cómo definirla en phpunit.xml sin usar `.env`.

## Entregable
`docs/auditorias/diagnostico-pruebas.md` con una tabla
(causa | pruebas afectadas | tipo | corrección aplicada o propuesta)
y el resultado de `php artisan test` antes y después.

## Restricciones
- No toques `.env`, dependencias, migraciones ni código en `app/`.
- Prohibido "arreglar" pruebas desactivándolas, con skip o eliminando asserts.
