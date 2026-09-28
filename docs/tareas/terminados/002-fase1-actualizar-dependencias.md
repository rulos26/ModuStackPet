---
agente: claude
estado: terminado
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

## Handoff
- Agente y fecha: Claude, 2026-09-27.
- Qué se hizo:
  - `composer audit --locked` ANTES: 52 avisos / 15 paquetes.
  - `composer install` + `php artisan test` (línea base): 31 failed, 1 passed,
    todas las fallas por `QueryException` al conectar a `mysql` (sin DB local
    disponible en este entorno; no es un fallo de código).
  - `composer update` (fue necesario `--no-blocking`: Composer 2.10 bloquea
    por defecto instalar versiones con avisos conocidos, y toda la línea
    11.31–11.56 de laravel/framework tiene al menos un aviso sin parche en
    11.x). No se cambió ninguna restricción de versión mayor en composer.json.
  - Reemplazado `nunomaduro/larastan` (abandonado) por `larastan/larastan`
    ^3.12 y actualizado `phpstan.neon`.
  - `composer audit --locked` DESPUÉS: 3 avisos / 1 paquete (laravel/framework,
    solo se corrigen subiendo a 12.x/13.x — Fase 2).
  - `php artisan test` DESPUÉS: mismo resultado, 31 failed, 1 passed, mismas
    causas de conexión a DB — sin regresiones nuevas.
  - `composer validate`: OK.
  - Detalle completo en [docs/auditorias/fase1-resultado.md](../../auditorias/fase1-resultado.md).
- Archivos modificados: `composer.json`, `composer.lock`, `phpstan.neon`,
  `docs/auditorias/fase1-resultado.md`, este archivo de tarea (movido a
  `terminados/`).
- Cómo probarlo:
  - `composer install`
  - `composer audit --locked` (debe mostrar solo 3 avisos de laravel/framework)
  - `php ./vendor/bin/phpstan analyse` (confirma que larastan/larastan carga)
  - Para pruebas funcionales reales: levantar el stack de Docker Compose del
    proyecto con MySQL y correr `php artisan test` (no se pudo hacer en esta
    sesión por falta de entorno local).
- Pendientes y riesgos:
  - 3 avisos de laravel/framework quedan sin corregir (requieren Laravel
    12/13, fuera de alcance de esta fase).
  - No se validó la suite de pruebas contra una base de datos real; solo se
    comparó que el número y tipo de fallas es idéntico antes/después.
  - `phpstan.neon` a nivel 10 reporta 1000+ hallazgos preexistentes en `app/`
    no relacionados con esta tarea; no se tocaron.
- Preguntas para el humano:
  - ¿Se puede levantar el entorno Docker/MySQL local para correr
    `php artisan test` con datos reales antes de mergear esta rama?
  - ¿Se agenda ya la Fase 2 (migración de Laravel a 12/13) para cerrar los
    3 avisos restantes?
