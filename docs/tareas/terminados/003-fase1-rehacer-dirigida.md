---
agente: claude
estado: terminado
rama: ia/claude/fase1-dirigida
archivos: [composer.json, composer.lock, phpstan.neon]
---

# Rehacer la Fase 1 como actualización dirigida

## Motivo
El PR #4 (unido a main en 9cb3f22c) introdujo saltos de versión mayor en
dependencias indirectas: phpseclib 3→4, google2fa 8→9, php-jwt 6→7,
css-parser 8→9, webmozart/assert 1→2. La tarea 002 prohibía cualquier salto mayor.

## Qué hacer
1. Crea la rama `ia/claude/fase1-dirigida` desde main actualizado.
2. Restaura el lock anterior al merge: `git checkout 9cb3f22c^1 -- composer.lock`.
3. Actualiza SOLO los paquetes vulnerables y larastan, con --with-dependencies:
   laravel/framework dompdf/dompdf barryvdh/laravel-dompdf guzzlehttp/guzzle
   guzzlehttp/psr7 league/commonmark phpseclib/phpseclib "symfony/*"
   phpunit/phpunit psy/psysh larastan/larastan nunomaduro/larastan
4. Compara la versión MAYOR de cada paquete contra el lock de `9cb3f22c^1`.
   Si alguna cambió (salvo el reemplazo de larastan), detente y repórtalo
   en el Handoff sin hacer commit.
5. Ejecuta `composer audit --locked` y `php artisan test`.

## Entregable
Actualiza `docs/auditorias/fase1-resultado.md` con los avisos restantes y su
motivo, los paquetes cambiados respecto a `9cb3f22c^1` y la confirmación
explícita de que no hay saltos de versión mayor.

## Restricciones
- No toques `.env`. No actualices laravel/fortify ni laravel/socialite.
- Ningún salto de versión mayor, directo o indirecto.

## Handoff
- Agente y fecha: Claude, 2026-09-27.
- Qué se hizo:
  - Restaurado `composer.lock`/`composer.json`/`phpstan.neon` a
    `9cb3f22c^1` (estado previo al merge de la tarea 002).
  - Reemplazado `nunomaduro/larastan` por `larastan/larastan` ^3.12 y
    actualizado `phpstan.neon`.
  - `composer update laravel/framework dompdf/dompdf barryvdh/laravel-dompdf
    guzzlehttp/guzzle guzzlehttp/psr7 league/commonmark phpseclib/phpseclib
    "symfony/*" phpunit/phpunit psy/psysh --with-dependencies --no-blocking`
    (sin `-W`, para no arrastrar todo el árbol).
  - Ese update igual arrastró `sabberworm/php-css-parser` 8→9 (permitido por
    `dompdf/php-svg-lib: ^8.4 || ^9.0`); se corrigió fijándolo temporalmente
    a `^8.9`, actualizando solo ese paquete, y luego retirando la
    dependencia directa espuria de `composer.json` con `composer update
    --lock` (solo recalcula el hash, no vuelve a resolver).
  - Verificado con un script PHP ad-hoc que ningún paquete del
    `composer.lock` final cambió de versión **mayor** respecto a
    `9cb3f22c^1`, salvo el reemplazo intencional de larastan.
    `phpseclib/phpseclib` quedó en 3.0.57 (antes 3.0.47), `pragmarx/google2fa`
    sin tocar en v8.0.3, `firebase/php-jwt` sin tocar en v6.11.1,
    `sabberworm/php-css-parser` en v8.9.0. `laravel/fortify` y
    `laravel/socialite` no se tocaron.
  - `composer audit --locked` DESPUÉS: 4 avisos / 2 paquetes
    (`laravel/framework` ×3 — requieren Laravel 12/13 — y `firebase/php-jwt`
    ×1 baja — requiere ^7.0, salto mayor prohibido por tocar socialite).
  - `php artisan test`: 27 failed, 4 warnings, 1 passed, **idéntico** antes
    (con el lock restaurado) y después (con la actualización dirigida). Las
    fallas son un `FOREIGN KEY constraint failed` preexistente en los
    seeders/tests (`user_id = 0`), no relacionado con dependencias.
  - `composer validate`: OK. `phpstan analyse` carga `larastan/larastan`
    correctamente.
  - No se tocó `.env` (no existe en este entorno; `phpunit.xml` ya trae
    sqlite en memoria y `APP_KEY` fijo desde el commit `6d97b116`).
  - Detalle completo en la sección "Corrección dirigida (tarea 003)" de
    [docs/auditorias/fase1-resultado.md](../../auditorias/fase1-resultado.md).
- Archivos modificados: `composer.json`, `composer.lock`, `phpstan.neon`,
  `docs/auditorias/fase1-resultado.md`, este archivo de tarea (movido a
  `terminados/`).
- Cómo probarlo:
  - `composer install && composer audit --locked` (4 avisos / 2 paquetes).
  - `composer validate`.
  - `php ./vendor/bin/phpstan analyse` (confirma que larastan/larastan carga).
  - `php artisan test` (usa sqlite en memoria vía `phpunit.xml`, no necesita
    `.env`; espera 27 failed / 4 warnings / 1 passed, igual que antes del
    cambio).
- Pendientes y riesgos:
  - Queda 1 aviso de `firebase/php-jwt` (severidad baja) sin corregir porque
    su fix es un salto mayor y la tarea prohíbe tocar `laravel/socialite`.
  - Los 3 avisos de `laravel/framework` siguen pendientes de la Fase 2
    (Laravel 12/13).
  - Las 27 fallas de tests son preexistentes (constraint de FK en
    `module_logs`/`user_id`), no se investigaron a fondo por estar fuera del
    alcance de esta tarea de dependencias.
- Preguntas para el humano:
  - ¿Se acepta dejar pendiente el aviso de `firebase/php-jwt` (severidad
    baja) hasta que se decida actualizar `laravel/socialite`, o prefieres
    que lo resuelva en una tarea aparte?
  - ¿Programamos ya la Fase 2 (Laravel 12/13) para cerrar los 3 avisos de
    `laravel/framework`?
  - ¿Investigo por separado el `FOREIGN KEY constraint failed` en los tests
    (parece un problema de datos de prueba, no de dependencias)?
