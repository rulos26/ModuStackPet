---
agente: claude
estado: pendiente
rama:
archivos: [composer.json, composer.lock, bootstrap/, config/, app/, routes/]
---

# Fase 2: migrar de Laravel 11 a Laravel 12

## Contexto
Laravel 11 no tiene soporte y quedan 3 avisos de seguridad de laravel/framework
que solo se corrigen en 12.x. Hay 32 pruebas que pasan: son la red de seguridad.

## Qué hacer
1. Sigue la guía oficial de actualización 11 → 12 (upgrade guide de laravel.com).
2. Cambia `laravel/framework` a `^12.0`. Actualiza SOLO los paquetes que
   Laravel 12 exija para resolver. Si Fortify o Socialite no necesitan cambiar,
   no los toques.
3. Aplica los cambios de código que exija la guía, nada más.
4. Ejecuta `composer audit --locked`, `php artisan test` y `composer validate`.

## Entregable
`docs/auditorias/fase2-laravel12.md` con:
- Tabla de TODOS los saltos de versión mayor (recuerda la regla de 0.x),
  cada uno con su motivo: qué paquete lo exige.
- Cambios de código aplicados y el punto de la guía que los justifica.
- Avisos de seguridad antes y después. Pruebas antes y después.
- Lista de funciones a probar manualmente si cambiaron Fortify (2FA),
  Socialite (login social) o dompdf (PDF).

## Restricciones
- No toques `.env` ni `.env.testing`. No desactives ni cambies pruebas.
- Si una prueba falla por la migración, detente y repórtalo en el Handoff.
