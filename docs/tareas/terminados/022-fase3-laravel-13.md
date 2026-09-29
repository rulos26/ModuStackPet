---
agente: claude
estado: terminada
rama: ia/claude/fase3-laravel-13
archivos: [composer.json, composer.lock, bootstrap/, config/, app/, routes/]
---

# Fase 3: migrar de Laravel 12 a Laravel 13

## Contexto
Laravel 13 requiere PHP >= 8.3 (ya disponible vía Herd en el sistema local,
seleccionable con `herd use 8.3`). Hay 144 pruebas que pasan: son la red de
seguridad. El tag `v2.0-post-auditoria` marca el punto de partida seguro al
que se puede volver si algo sale mal.

## Qué hacer
1. Verifica primero: `php --version` debe reportar 8.3.x. Si reporta 8.2,
   detente y repórtalo en el Handoff (el humano debe cambiar la versión
   global de PHP en Herd antes de continuar).
2. Sigue la guía oficial de actualización 12 -> 13 de laravel.com/docs.
   Busca en la web la guía actual si no la tienes en el contexto; no
   improvises pasos.
3. composer.json: PHP a "^8.3" y laravel/framework a "^13.0".
4. Actualiza SOLO los paquetes que Laravel 13 exija para resolver
   dependencias. No toques Fortify, Socialite ni dompdf salvo que el propio
   `composer update` lo exija.
5. Aplica los cambios de código que pida la guía oficial, y nada más.
6. Ejecuta `composer audit --locked`, `php artisan test` y `composer validate`.

## Entregable
`docs/auditorias/fase3-laravel13.md`:
- Tabla de TODOS los cambios de versión mayor (recuerda la regla de
  paquetes 0.x en AGENTS.md), cada uno con el motivo (qué lo exige).
- Cambios de código aplicados y el punto exacto de la guía oficial que
  los justifica (con enlace).
- Avisos de seguridad antes y después. Pruebas antes y después.
- Lista de funciones a probar manualmente si cambiaron Fortify (2FA),
  Socialite (login social) o dompdf (generación de PDF).
- Si algo de la guía oficial no aplica a este proyecto (por ejemplo, no usa
  una función que cambió), decláralo explícitamente en vez de omitirlo en
  silencio.

## Restricciones
- No toques `.env` ni `.env.testing`. No desactives ni cambies pruebas
  existentes: si una prueba falla por la migración, detente y repórtalo
  en el Handoff sin "arreglarla" para que pase.
- Si algo no está cubierto por la guía oficial y requiere una decisión de
  producto, detente y pregunta en el Handoff en vez de decidir tú.
- Al terminar, vuelve con `git switch --detach origin/main`.

## Handoff
- Agente y fecha: Claude, 2026-09-28.
- Qué se hizo: `php --version` confirmó 8.3.33 (no hizo falta detenerse).
  Se consultó la guía oficial de actualización 12→13 de laravel.com/docs
  vía WebFetch. Se subió `composer.json` (`php` a `^8.3`, `laravel/framework`
  a `^13.0`, `laravel/tinker` a `^3.0`, `phpunit/phpunit` a `^12.0`, los 4
  paquetes que la guía exige explícitamente) y se corrió `composer update
  --with-all-dependencies`. `laravel/fortify`, `laravel/socialite`,
  `spatie/laravel-permission` subieron de versión menor solo porque
  Composer lo resolvió dentro de sus rangos `^` ya existentes; no se tocó
  su declaración en `composer.json`. `barryvdh/laravel-dompdf` no cambió de
  versión. Se revisó cada sección de la guía oficial contra el código del
  proyecto: ninguna exigía cambios de código (detalle exhaustivo, sección
  por sección, en el entregable).
- Archivos modificados: `composer.json`, `composer.lock`,
  `docs/auditorias/fase3-laravel13.md` (nuevo). Ningún archivo de
  `bootstrap/`, `config/`, `app/` ni `routes/` necesitó cambios de código.
- Cómo probarlo: `php artisan test` (144 pruebas, 436 aserciones, verde,
  igual que antes de la migración), `composer validate` (OK),
  `composer audit --locked` (sin avisos; el único aviso preexistente sobre
  `firebase/php-jwt` se resolvió como efecto colateral),
  `php artisan --version` (`Laravel Framework 13.33.0`).
- Pendientes y riesgos: ver la lista de "Funciones a probar manualmente"
  en el entregable (2FA de Fortify vía `pragmarx/google2fa` v8→v9, login
  social de Socialite, generación de PDF con dompdf) — ninguna tiene
  cobertura de pruebas automatizadas en este proyecto. `laravel/passkeys`
  v0.2.1 (0.x) entró como dependencia transitiva nueva de Fortify 1.40,
  pero está inactiva (`config/fortify.php` no incluye
  `Features::passkeys()`).
- Preguntas para el humano: la guía describe la nueva opción
  `config/session.php` → `'serialization'`, que en apps nuevas viene en
  `'json'` (más seguro contra deserialización de objetos si se filtra
  `APP_KEY`) pero en `'php'` mantiene sesiones activas sin interrupción.
  Nuestro `config/session.php` no tiene esa clave (nunca se sincronizó
  desde el skeleton), así que hoy el framework usa internamente `'php'`
  (sin cambio de comportamiento). Es una decisión de producto: ¿agregar la
  clave con `'json'` ahora (forzando cierre de sesión de todos los
  usuarios activos en el próximo deploy) o dejarlo en `'php'` por ahora y
  planear la migración a `'json'` en una ventana anunciada? No se decidió
  unilateralmente.
