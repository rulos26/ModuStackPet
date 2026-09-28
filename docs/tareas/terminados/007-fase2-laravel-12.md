---
agente: claude
estado: terminado
rama: ia/claude/fase2-laravel-12
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

## Handoff
- Agente y fecha: claude, 2026-09-28.
- Qué se hizo: `laravel/framework` actualizado de `^11.31` (v11.56.1) a
  `^12.61` (v12.69.2) vía `composer update laravel/framework
  --with-all-dependencies`. Se fijó `^12.61` en lugar de `^12.0` porque
  Composer bloqueó por avisos de seguridad las versiones 12.0.0–12.60.x.
  No se requirió ningún cambio de código: `bootstrap/app.php` ya usaba la
  estructura sin `Kernel.php` de Laravel 11, que Laravel 12 conserva.
  Detalle completo en `docs/auditorias/fase2-laravel12.md`.
- Archivos modificados: `composer.json`, `composer.lock`.
- Cómo probarlo: `php artisan test` (32 passed, 70 assertions, igual que
  antes de la migración); `composer validate` (válido); `composer audit
  --locked` (bajó de 4 avisos/2 paquetes a 1 aviso/1 paquete).
- Pendientes y riesgos: queda 1 aviso de seguridad de `firebase/php-jwt`
  (bajo, cifrado débil), fuera de alcance de esta tarea porque lo exige
  `laravel/socialite` (`^6.4`, que solo cubre versiones `<7.0.0` de
  php-jwt). Requeriría actualizar `laravel/socialite` en una tarea aparte.
  No se hicieron pruebas manuales de 2FA (Fortify), login social
  (Socialite) ni generación de PDF (dompdf) — ninguno de esos paquetes
  cambió de versión, pero se recomienda verificarlos manualmente antes de
  desplegar por tratarse de un salto mayor del framework.
- Preguntas para el humano: ¿se autoriza una tarea separada para evaluar
  la actualización de `laravel/socialite` y así resolver el aviso de
  `firebase/php-jwt`, o se acepta el riesgo (severidad baja) por ahora?
