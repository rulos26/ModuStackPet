---
agente: claude
estado: en-curso
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
