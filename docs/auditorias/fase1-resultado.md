# Fase 1 — Resultado de actualización de dependencias

Rama: `ia/claude/fase1-actualizar-dependencias`
Fecha: 2026-09-27

## Avisos de seguridad (`composer audit --locked`)

### Antes
- **52 avisos** en **15 paquetes**.

### Después
- **3 avisos** en **1 paquete** (`laravel/framework`).

Avisos que quedan y motivo (no se pueden corregir sin subir de versión mayor,
fuera del alcance de la Fase 1):

| Advisory ID | Severidad | Título | Motivo |
|---|---|---|---|
| PKSA-m5cs-t1y6-qpcs | media | Laravel Framework: Temporary Signed URL Path Confusion | Corregido en `laravel/framework` ^12.61.1 / ^13.12.0. Nuestro rango es `^11.31`; la migración a Laravel 12 es la Fase 2. |
| PKSA-3r5d-mb8f-1qw9 | alta | Laravel Framework: CRLF injection in default email rule | Corregido en ^12.60.0 / ^13.9.0+. Mismo motivo. |
| PKSA-mdq4-51ck-6kdq (CVE-2026-48019) | — | Laravel CRLF injection in default email rule | Afecta a toda la rama 9.x–13.9.x incluyendo 11.x; el fix definitivo llega con Laravel 12+. |

Nota: durante `composer update` fue necesario usar `--no-blocking` porque
Composer 2.10 bloquea por defecto la resolución hacia cualquier versión con
avisos conocidos (incluidas las de 11.x aún sin parche). Sin esa bandera no
resuelve ninguna versión de `laravel/framework` dentro de `^11.31`.

## Paquetes actualizados (dentro de las restricciones actuales)

Directos (`require` / `require-dev`):

| Paquete | Antes | Después |
|---|---|---|
| laravel/framework | v11.44.2 | v11.56.1 |
| laravel/fortify | v1.25.4 | v1.40.0 |
| laravel/socialite | v5.23.1 | v5.31.0 |
| laravel/tinker | v2.10.1 | v2.11.1 |
| barryvdh/laravel-dompdf | v3.1.1 | v3.1.2 |
| ibex/crud-generator | v2.1.5 | v2.1.9 |
| laravel/pail | v1.2.2 | v1.2.7 |
| laravel/pint | v1.22.0 | v1.30.4 |
| laravel/sail | v1.41.0 | v1.68.0 |
| mockery/mockery | 1.6.12 | 1.6.15 |
| nunomaduro/collision | v8.8.0 | v8.9.5 |
| nunomaduro/larastan | v3.3.1 | **reemplazado por** larastan/larastan v3.12.2 |
| phpunit/phpunit | 11.5.17 | 11.5.56 |
| spatie/laravel-permission | 6.17.0 | 6.25.0 |

Transitivos relevantes (actualizados automáticamente por `composer update`):
dompdf/dompdf v3.1.0→v3.1.6, guzzlehttp/guzzle 7.9.3→7.15.5, symfony/*
(console, string, translation, yaml, finder, clock, serializer, etc.)
2.x/7.2→7.4, nesbot/carbon 3.9.0→3.14.1, monolog/monolog 3.9.0→3.12.0,
league/flysystem 3.29.1→3.36.0, phpseclib/phpseclib 3.0.47→4.0.1,
pragmarx/google2fa v8.0.3→v9.1.0, firebase/php-jwt v6.11.1→v7.2.0, entre
otros. Se agregó `laravel/passkeys` y sus dependencias (`web-auth/webauthn-lib`,
`spomky-labs/*`, `symfony/property-*`) como nueva dependencia transitiva de
`laravel/fortify` ^1.40.

`laravel/framework` permanece en `^11.31` (sin cambio de versión mayor, según
restricción de la tarea).

## Cambio adicional: larastan → larastan/larastan

`nunomaduro/larastan` está abandonado (aviso de Composer). Se reemplazó por
`larastan/larastan` ^3.12 y se actualizó la referencia en
[phpstan.neon](../../phpstan.neon):

```diff
- ./vendor/nunomaduro/larastan/extension.neon
+ ./vendor/larastan/larastan/extension.neon
```

Se verificó que `php ./vendor/bin/phpstan analyse` carga la extensión y
ejecuta el análisis correctamente (nivel 10 configurado en el repo; reporta
1000+ hallazgos de tipado/documentación preexistentes en `app/`, no
relacionados con esta tarea — fuera de alcance corregirlos aquí).

## Resultado de las pruebas

### Antes y después (idéntico)
```
Tests:    31 failed, 1 passed (2 assertions)
Duration: ~189s
```

Las 31 fallas son **todas** `QueryException: SQLSTATE[HY000] [2002] No se
puede establecer una conexión...` contra `mysql` (host/credenciales de
`.env.example`, que apuntan a una base remota no accesible desde este
entorno). No hay entorno Docker/MySQL local levantado en esta sesión, así que
no fue posible migrar ni conectar. **No se pudo verificar el comportamiento
real de las pruebas contra una base de datos**; el conteo de fallas es idéntico
antes y después del `composer update`, lo que indica que la actualización no
introdujo regresiones nuevas, pero no constituye una validación funcional
completa.

`.env` se creó copiando `.env.example` (solo placeholders, sin secretos) y se
generó `APP_KEY` con `php artisan key:generate`, solo para poder ejecutar
`php artisan test`; no se leyeron ni escribieron credenciales reales.

`composer validate`: OK.

## Pendientes y riesgos
- 3 avisos de `laravel/framework` sin corregir: requieren Laravel 12/13
  (Fase 2).
- No se pudo correr la suite de pruebas contra una base de datos real
  (sin Docker/MySQL disponible en este entorno). Se recomienda levantar el
  stack de Docker Compose del proyecto y repetir `php artisan test` antes de
  mergear, o habilitar temporalmente `sqlite`/`:memory:` en `phpunit.xml`
  para CI.
- `phpstan.neon` reporta 1000+ hallazgos de nivel 10 preexistentes en `app/`;
  no se tocaron porque están fuera del alcance de esta tarea.
