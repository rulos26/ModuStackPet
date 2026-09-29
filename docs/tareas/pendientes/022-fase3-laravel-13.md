---
agente: claude
estado: pendiente
rama:
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
