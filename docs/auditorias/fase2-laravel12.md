# Fase 2: migración de Laravel 11 a Laravel 12

Fecha: 2026-09-28
Agente: claude (rama `ia/claude/fase2-laravel-12`)

## Resumen

Actualización de `laravel/framework` de `^11.31` (instalado v11.56.1) a
`^12.61` (instalado v12.69.2), siguiendo la guía oficial de actualización
11 → 12 de laravel.com. Laravel 12 es una actualización de bajo impacto:
no introdujo cambios de código en este proyecto porque `bootstrap/app.php`
ya usaba la estructura de configuración introducida en Laravel 11 (sin
`app/Http/Kernel.php` ni `app/Console/Kernel.php`), que Laravel 12 conserva
sin cambios.

Se fijó `^12.61` (no `^12.0`) porque Composer bloqueó por política de
seguridad las versiones `12.0.0`–`12.60.x`, afectadas por 2 de los 3 avisos
de `laravel/framework` corregidos abajo.

## Tabla de saltos de versión mayor

| Paquete | Antes | Después | Salto mayor | Motivo |
|---|---|---|---|---|
| `laravel/framework` | v11.56.1 | v12.69.2 | Sí (11 → 12) | Objetivo de esta tarea: quitar 3 avisos de seguridad solo corregidos en 12.x |
| `symfony/polyfill-php84` | (no instalado) | v1.38.1 | N/A (paquete nuevo) | Dependencia transitiva nueva que trae `laravel/framework` v12; no es un salto de versión de un paquete existente |

Ningún otro paquete de `require` o `require-dev` cambió de versión. No fue
necesario tocar `laravel/fortify`, `laravel/socialite`, `laravel/tinker`,
`barryvdh/laravel-dompdf`, `spatie/laravel-permission`, `laravel/pint`,
`phpunit/phpunit`, `larastan/larastan`, `laravel/pail` ni `laravel/sail`:
sus rangos de versión ya eran compatibles con Laravel 12 y Composer no
exigió actualizarlos.

No hay ningún paquete en 0.x en el árbol de dependencias directas, así que
la regla de SemVer 0.x (AGENTS.md) no aplica a este cambio.

## Cambios de código aplicados

Ninguno. Según la guía oficial de actualización 11 → 12
(https://laravel.com/docs/12.x/upgrade), los cambios de comportamiento
relevantes para Laravel 12 son:
- Requisito mínimo de PHP `^8.2` (ya cumplido).
- Requisito mínimo de `nesbot/carbon` `^3.8` (resuelto automáticamente por
  Composer al actualizar `laravel/framework`, sin declaración directa en
  `composer.json` de este proyecto).
- Estructura de `bootstrap/app.php` sin `Kernel.php` (el proyecto ya la
  usaba desde antes, por venir de Laravel 11).

No se encontró ningún uso en `app/`, `routes/` ni `config/` de las APIs
marcadas como eliminadas o con cambio de comportamiento en la guía
(p. ej. `Illuminate\Support\ItemNotFoundException`,
`Illuminate\Container\Container::runningUnitTests()`, cambios en
`config/database.php`), por lo que no se requirió ninguna modificación en
`app/`, `config/`, `routes/` ni `bootstrap/`.

Único cambio de archivo: `composer.json` (línea de `laravel/framework`) y
`composer.lock` (regenerado por `composer update laravel/framework
--with-all-dependencies`).

## Avisos de seguridad: antes y después

**Antes** (`composer audit --locked`, con `laravel/framework` v11.56.1):
4 avisos afectando 2 paquetes:
1. `firebase/php-jwt` (bajo) — cifrado débil, corregido en `^7.0`.
2. `laravel/framework` (medio) — confusión de ruta en URLs firmadas
   temporales, corregido en `12.61.1`.
3. `laravel/framework` (alto) — inyección CRLF en la regla de validación
   `email` por defecto, corregido en `12.60.0`.
4. `laravel/framework` (sin severidad publicada, CVE-2026-48019) — mismo
   CRLF, aviso duplicado del anterior con distinto identificador.

**Después** (con `laravel/framework` v12.69.2):
1 aviso afectando 1 paquete:
1. `firebase/php-jwt` (bajo) — sigue igual, **fuera de alcance de esta
   tarea**. Lo trae `laravel/socialite` v5.23.1, que exige
   `firebase/php-jwt: ^6.4` (rango que solo incluye versiones `<7.0.0`,
   todas afectadas). Corregirlo requeriría actualizar `laravel/socialite`
   a una versión que exija `firebase/php-jwt ^7.0`, lo cual está fuera del
   alcance de "actualizar solo lo que Laravel 12 exige" de esta tarea.
   Recomendación: crear una tarea separada para evaluar la actualización
   de `laravel/socialite`.

Los 3 avisos de `laravel/framework` quedaron resueltos.

## Pruebas: antes y después

- **Antes**: `php artisan test` → 32 passed (70 assertions).
- **Después**: `php artisan test` → 32 passed (70 assertions). Sin
  cambios en la suite, sin pruebas modificadas ni desactivadas.
- `composer validate` → `./composer.json is valid` (antes y después).

## Funciones a probar manualmente

Fortify y Socialite **no cambiaron de versión** en esta migración (sus
constraints en `composer.json` no se tocaron y Composer no forzó
actualizarlos), y `barryvdh/laravel-dompdf` tampoco cambió. Por lo tanto,
no se identifican funciones específicas de 2FA, login social o generación
de PDF en riesgo por este cambio. Aun así, por tratarse de un salto mayor
del framework, se recomienda una verificación manual general antes de
desplegar:
- Login y registro (Fortify).
- Flujo de 2FA si está habilitado en algún entorno.
- Login social vía Socialite (Google/otros proveedores configurados).
- Generación de PDFs (dompdf) en los módulos que los usan.
- Rutas y middleware personalizados (verificar que `bootstrap/app.php`
  siga registrando todo correctamente en runtime, no solo en pruebas).

## Restricciones respetadas

- No se tocó `.env` ni `.env.testing`.
- No se desactivó ni modificó ninguna prueba.
- No fue necesario detenerse por fallos de pruebas: la suite pasó completa
  en el primer intento tras la actualización.
