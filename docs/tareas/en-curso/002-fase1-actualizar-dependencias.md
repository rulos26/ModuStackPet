---
agente: claude
estado: en-curso
rama: ia/claude/fase1-actualizar-dependencias
archivos: [composer.json, composer.lock]
---

# Fase 1: corregir vulnerabilidades sin cambios de versión mayor

## Contexto
`composer audit --locked` reporta 52 avisos en 15 paquetes. Unos 48 se
corrigen actualizando dentro de las restricciones actuales de composer.json.
Laravel se queda en 11.x (su migración es la tarea de la Fase 2).

## Qué hacer
1. Guarda la salida de `composer audit --locked --format=plain` (estado ANTES).
2. `composer install` y ejecuta `php artisan test` para tener la línea base.
   Si las pruebas necesitan `.env`, NO lo leas ni lo crees con secretos:
   detente y pregunta.
3. `composer update` SIN cambiar restricciones de versión mayor en composer.json.
4. Reemplaza `nunomaduro/larastan` por `larastan/larastan` (require-dev) y
   ajusta `phpstan.neon` si lo referencia.
5. Repite `php artisan test` y `composer audit --locked` (estado DESPUÉS).

## Entregable
`docs/auditorias/fase1-resultado.md` con:
- Avisos antes y después (número y lista de los que quedan con su motivo).
- Paquetes actualizados (versión anterior y nueva).
- Resultado de las pruebas antes y después.

## Restricciones
- Prohibido subir laravel/framework a 12 o cualquier paquete a una versión mayor.
- Si una prueba que pasaba empieza a fallar, detente y repórtalo en el Handoff.
