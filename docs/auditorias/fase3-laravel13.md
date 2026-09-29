# Fase 3: migración de Laravel 12 a Laravel 13

Fecha: 2026-09-28
Agente: Claude
Rama: `ia/claude/fase3-laravel-13`
Punto de partida seguro: tag `v2.0-post-auditoria`.

## Verificación previa

- `php --version`: `PHP 8.3.33 (cli)` — cumple el requisito de Laravel 13
  (PHP >= 8.3). No fue necesario detenerse a pedir un cambio de versión en
  Herd.
- Guía oficial consultada: [Upgrade Guide — Laravel 13.x](https://laravel.com/docs/13.x/upgrade)
  (obtenida vía WebFetch el mismo día; no estaba en el contexto previo).
- Antes de tocar nada: `php artisan test` → **144 pruebas, 436
  aserciones**, todas en verde. `composer validate` → OK.
  `composer audit --locked` → 1 aviso de severidad baja, preexistente y sin
  relación con esta migración: `firebase/php-jwt` < 7.0.0,
  [PKSA-y2cr-5h3j-g3ys](https://packagist.org/security-advisories/PKSA-y2cr-5h3j-g3ys)
  / [CVE-2025-45769](https://www.cve.org/CVERecord?id=CVE-2025-45769)
  ("weak encryption"). Ese paquete es una dependencia transitiva (de
  Socialite/Fortify), no algo que el proyecto declare directamente.

## Cambios en `composer.json`

Siguiendo la sección
["Updating Dependencies" (High impact)](https://laravel.com/docs/13.x/upgrade#updating-dependencies)
de la guía oficial:

| Paquete | Antes | Después | Motivo |
|---|---|---|---|
| `php` | `^8.2` | `^8.3` | Requisito mínimo de Laravel 13. |
| `laravel/framework` | `^12.61` | `^13.0` | Objetivo de esta tarea. |
| `laravel/tinker` | `^2.9` | `^3.0` | La guía lo exige explícitamente. |
| `phpunit/phpunit` (dev) | `^11.0.1` | `^12.0` | La guía lo exige explícitamente. |

La guía también pide `laravel/boost` `^2.0` y `pestphp/pest` `^4.0`. **No
aplican**: el proyecto no usa Laravel Boost (no está en `composer.json`) ni
Pest (solo aparece `pestphp/pest-plugin` en `allow-plugins`, heredado del
scaffold, sin que el paquete `pestphp/pest` esté requerido; la suite usa
PHPUnit puro). Se declara aquí en vez de omitirlo en silencio.

No se tocó `barryvdh/laravel-dompdf`, `laravel/fortify`, `laravel/socialite`
ni `spatie/laravel-permission` en `composer.json`: sus rangos `^` ya
permitían que `composer update` resolviera versiones compatibles con
Laravel 13 sin ampliar el rango.

## `composer update --with-all-dependencies`

### Paquetes directos actualizados (dentro de su mismo rango declarado)

| Paquete | Antes | Después | Motivo |
|---|---|---|---|
| `laravel/framework` | 12.69.2 | **13.33.0** | Objetivo de la tarea. |
| `laravel/tinker` | 2.10.1 | **3.0.2** | Exigido por la guía (mayor). |
| `phpunit/phpunit` (dev) | 11.5.56 | **12.5.36** | Exigido por la guía (mayor). |
| `laravel/fortify` | 1.25.4 | 1.40.0 | Composer lo resolvió dentro de `^1.25` para satisfacer la compatibilidad con `laravel/framework ^13`. No se editó su restricción en `composer.json` (restricción de la tarea). |
| `laravel/socialite` | 5.23.1 | 5.31.0 | Igual: resuelto dentro de `^5.23`. |
| `spatie/laravel-permission` | 6.17.0 | 6.25.0 | Igual: resuelto dentro de `^6.10`. |
| `barryvdh/laravel-dompdf` | 3.1.2 | **3.1.2 (sin cambio)** | `composer update` no lo tocó; confirma que Laravel 13 no lo exige. |
| `ibex/crud-generator` (dev) | 2.1.5 | 2.1.9 | Parche, resuelto por dependencias de Laravel 13. |
| `laravel/pail` (dev) | 1.2.2 | 1.2.7 | Parche. |
| `laravel/pint` (dev) | 1.22.0 | 1.32.1 | Menor. |
| `laravel/sail` (dev) | 1.41.0 | 1.68.0 | Menor. |
| `mockery/mockery` (dev) | 1.6.12 | 1.6.15 | Parche. |
| `nunomaduro/collision` (dev) | 8.8.0 | 8.9.5 | Menor. |

### Cambios de versión MAYOR en dependencias transitivas

Regla de AGENTS.md aplicada: en un paquete 0.x, un cambio del segundo
número (`0.x` → `0.y`) cuenta como salto mayor.

| Paquete | Antes | Después | Motivo (qué lo exige) |
|---|---|---|---|
| `guzzlehttp/guzzle` | 7.15.5 | **8.2.0** | Requerido por `laravel/framework ^13`. |
| `guzzlehttp/psr7` | 2.13.1 | **3.1.0** | Requerido por `guzzlehttp/guzzle ^8`. |
| `guzzlehttp/promises` | 2.5.3 | **3.0.2** | Requerido por `guzzlehttp/guzzle ^8`. |
| `guzzlehttp/uri-template` | v1.0.11 | **v2.0.1** | Requerido por `guzzlehttp/guzzle ^8`. |
| `brick/math` | 0.14.8 (0.x) | **1.0.0** | Requerido por `laravel/framework ^13`. Cruza de 0.x a 1.0: bajo la regla de AGENTS.md, ya el salto `0.14` habría contado como mayor; ahora además cruza a estable. |
| `firebase/php-jwt` | v6.11.1 | **v7.2.1** | Requerido por `laravel/socialite`/`laravel/fortify` actualizados. Efecto colateral: resuelve el aviso de seguridad PKSA-y2cr-5h3j-g3ys detectado antes de la migración (ver abajo). |
| `sabberworm/php-css-parser` | v8.9.0 | **v9.5.0** | Requerido por `barryvdh/laravel-dompdf` (dependencia de dompdf, aunque dompdf en sí no cambió de versión). |
| `phpseclib/phpseclib` | 3.0.57 | **4.0.1** | Requerido por `laravel/socialite`/OAuth actualizados. |
| `pragmarx/google2fa` | v8.0.3 | **v9.1.0** | Requerido por `laravel/fortify ^1.40`. **Implementa el TOTP de 2FA que este proyecto usa activamente** (`config/fortify.php` tiene `Features::twoFactorAuthentication` habilitado) → ver lista de pruebas manuales. |
| `hamcrest/hamcrest-php` (dev) | v2.0.1 | **v3.0.0** | Requerido por `mockery/mockery` actualizado (solo tests). |
| `sebastian/*` (dev, ~12 paquetes internos de PHPUnit: `version`, `type`, `recursion-context`, `object-reflector`, `object-enumerator`, `global-state`, `exporter`, `environment`, `diff`, `comparator`, `cli-parser`, `complexity`, `lines-of-code`) | v5-v7 según paquete | v6-v8 según paquete | Churn interno normal de `phpunit/phpunit ^12`; son dependencias de desarrollo del test runner, no del código de la aplicación. Se listan aquí para no omitirlas, sin fila individual por ser ruido repetitivo. |

### Paquetes nuevos (no existían antes de la migración)

Todos transitivos, arrastrados por `laravel/fortify ^1.40`, que agregó
soporte de passkeys/WebAuthn como característica **opcional** del paquete:

- `laravel/passkeys` **v0.2.1** (0.x — pre-1.0, sujeto a cambios rotos en
  cualquier versión menor futura según la regla de AGENTS.md).
- `web-auth/webauthn-lib` 5.3.9, `web-auth/cose-lib` 4.8.2,
  `spomky-labs/cbor-php` 3.4.2, `spomky-labs/pki-framework` 1.6.3.
- `symfony/serializer`, `symfony/type-info`, `symfony/property-info`,
  `symfony/property-access` (todas v7.4.x).
- `phpstan/phpdoc-parser`, `phpdocumentor/reflection-common`,
  `phpdocumentor/reflection-docblock`, `phpdocumentor/type-resolver`,
  `doctrine/deprecations`, `webmozart/assert` (dev, vía `larastan`).

**Verificado**: `config/fortify.php` → el arreglo `'features'` **no**
incluye `Features::passkeys()`; solo tiene `registration`,
`resetPasswords`, `emailVerification`, `updateProfileInformation`,
`updatePasswords` y `twoFactorAuthentication`. La característica de
passkeys quedó instalada en `vendor/` pero **inactiva**: no cambia ningún
comportamiento observable de la aplicación.

## Cambios de código exigidos por la guía oficial

Se revisó cada sección de la guía (High/Medium/Low impact) contra el
código de este proyecto:

| Sección de la guía | Aplica a este proyecto | Acción |
|---|---|---|
| [Request Forgery Protection](https://laravel.com/docs/13.x/upgrade#request-forgery-protection) (High) | No | `grep` de `VerifyCsrfToken`/`ValidateCsrfToken` en `app/`, `bootstrap/`, `routes/`, `tests/`, `config/`: sin coincidencias. `bootstrap/app.php` no excluye CSRF de ninguna ruta. El alias viejo sigue funcionando igual (deprecado pero presente), así que tampoco hay urgencia. Sin cambios. |
| [Cache `serializable_classes`](https://laravel.com/docs/13.x/upgrade#cache-serializable_classes-configuration) (Medium) | No | Se auditó todo uso de `Cache::` en `app/`: `ModulesSyncCommand` cachea arrays de strings (`pluck('slug')->toArray()`), `Configuracion::getValor` cachea un valor escalar. Se revisó también `spatie/laravel-permission` (que sí cachea el árbol de permisos): su `PermissionRegistrar::getSerializedPermissionsForCache()` devuelve un **array** plano, no objetos Eloquent serializados; los rehidrata manualmente después de leer la caché. Ningún punto del proyecto guarda objetos PHP en caché. `config/cache.php` no tiene la clave `serializable_classes` (no viene en el archivo publicado en Laravel 12); no se agregó porque no hay nada que permitir explícitamente. |
| [Database `upsert` con MySQL/MariaDB](https://laravel.com/docs/13.x/upgrade#database-upsert-mariadb-mysql) (Medium) | No | Sin usos de `->upsert(` en `app/` ni `database/`. |
| [Cache Prefixes and Session Cookie Names](https://laravel.com/docs/13.x/upgrade#cache-prefixes-and-session-cookie-names) (Low) | No | `config/cache.php` (`'prefix'`) y `config/session.php` (`'cookie'`) ya definen explícitamente el patrón antiguo (`Str::slug(..., '_')`) en el propio archivo publicado del proyecto — no dependen del fallback interno del framework que cambió. El valor generado no cambia. |
| [Session `serialization` Configuration](https://laravel.com/docs/13.x/upgrade#session-serialization-configuration) (Low) | Ver pregunta al humano | `config/session.php` no tiene la clave `'serialization'` (no existía en el skeleton de Laravel 12). Al no estar publicada, el framework usa su valor interno de compatibilidad (`'php'`), igual que antes: **no hay cambio de comportamiento por la sola actualización**. No se agregó la clave porque decidir entre `'php'` (sin impacto) y `'json'` (más seguro contra gadget chains, pero invalida todas las sesiones activas al desplegar) es una decisión de producto. Ver Handoff. |
| [Domain Route Registration Precedence](https://laravel.com/docs/13.x/upgrade#domain-route-registration-precedence) (Low) | No | Sin `Route::domain(...)` en `routes/web.php` ni en `app/`. |
| [`JobAttempted` Event](https://laravel.com/docs/13.x/upgrade#jobattempted-event-exception-payload) / [`QueueBusy` Event](https://laravel.com/docs/13.x/upgrade#queuebusy-event-property-rename) (Low) | No | Sin listeners de `JobAttempted` ni `QueueBusy` en `app/`. |
| [Manager `extend` Callback Binding](https://laravel.com/docs/13.x/upgrade#manager-extend-callback-binding) (Low) | No | Sin usos de `::extend(`/`->extend(` para managers/drivers personalizados en `app/`. |
| [MySQL `DELETE` con `JOIN`/`ORDER BY`/`LIMIT`](https://laravel.com/docs/13.x/upgrade#mysql-delete-queries-with-join-order-by-and-limit) (Low) | No | Sin consultas `DELETE` con `JOIN` en el código. |
| [Pagination Bootstrap View Names](https://laravel.com/docs/13.x/upgrade#pagination-bootstrap-view-names) (Low) | No | Sin referencias directas a `pagination::default` / `pagination::simple-default` en `resources/` ni `app/`. |
| [Polymorphic Pivot Table Name Generation](https://laravel.com/docs/13.x/upgrade#polymorphic-pivot-table-name-generation) (Low) | No | Sin `morphToMany`/`morphedByMany`/`MorphPivot` personalizados en `app/Models`. |
| [`Str` Factories Reset Between Tests](https://laravel.com/docs/13.x/upgrade#str-factories-reset-between-tests) (Low) | No | Sin `Str::createRandomStringsUsing` ni fábricas de `Str` personalizadas en `tests/`. |
| [Symfony PHP 8.5 Polyfill / `array_first`/`array_last`](https://laravel.com/docs/13.x/upgrade#symfony-php-8.5-polyfill-and-global-function-conflicts) (Low) | No | Sin `array_first(`/`array_last(` en `app/` ni `tests/`; el proyecto no usa `laravel/helpers` ni helpers globales con esos nombres. |
| Resto de items *Very Low* (contratos `Store`/`Repository`/`Dispatcher`/`ResponseFactory`/`MustVerifyEmail`/`Queue`, `Container::call` nullable, HTTP Client `throw`/`throwIf`, asunto de email de reset de contraseña, `Js::from`, `withScheduling`, notificaciones en cola) | No | Ninguno de estos se implementa/override en `app/`: no hay contratos custom (`Store`, `Repository`, `Dispatcher`, `ResponseFactory`, `MustVerifyEmail`, `Queue`), ni `$container->call()` con parámetros nullable relevantes, ni overrides de `Response::throw`, ni tests que dependan del asunto exacto del correo de reset, ni usos de `Js::from`, ni `withScheduling`, ni notificaciones con `#[DeleteWhenMissingModels]`. |

**Conclusión**: la guía oficial no exigió ningún cambio de código en este
proyecto. Todos los "High/Medium impact" que sí podían tocarlo se
verificaron como no aplicables tras inspección directa del código (no se
descartaron por suposición).

## Verificación posterior

- `php artisan test`: **144 pruebas, 436 aserciones**, todas en verde
  (mismo conteo que antes de la migración; ninguna prueba se modificó,
  deshabilitó ni se le bajó cobertura).
- `composer validate`: OK.
- `composer audit --locked`: **sin avisos de seguridad** (el único aviso
  preexistente, sobre `firebase/php-jwt`, se resolvió como efecto
  colateral de actualizar `laravel/socialite`/`laravel/fortify`).
- `php artisan --version`: `Laravel Framework 13.33.0`.

## Funciones a probar manualmente

La guía no dio instrucciones de código para estas áreas, pero sus
dependencias subieron de versión mayor y son sensibles a regresiones de
comportamiento no cubiertas por la suite automatizada:

- **Fortify — 2FA** (`pragmarx/google2fa` v8 → v9, `laravel/fortify` 1.25 →
  1.40): activar 2FA en un usuario, escanear el QR con una app TOTP,
  confirmar el código, cerrar sesión y volver a iniciar sesión pidiendo el
  código; probar también los códigos de recuperación.
- **Socialite — login social** (`laravel/socialite` 5.23 → 5.31,
  `league/oauth1-client`, `firebase/php-jwt` v6 → v7): iniciar sesión con
  cada proveedor OAuth configurado en `superadmin.oauth-providers.*` y
  confirmar que el flujo de callback crea/vincula el usuario
  correctamente.
- **dompdf — generación de PDF** (`sabberworm/php-css-parser` v8 → v9,
  dependencia transitiva de dompdf aunque `barryvdh/laravel-dompdf` no
  cambió de versión): generar un PDF desde `PDFController` (certificado de
  mascota) y desde `EmpresaController` (PDF de empresa), y revisar que el
  CSS embebido (bordes, tablas, imágenes) se siga renderizando igual.

## Restricciones respetadas

- No se tocó `.env` ni `.env.testing`.
- Ninguna prueba existente se modificó, deshabilitó ni se "arregló para
  que pase"; las 144 pruebas pasaron sin cambios de código de aplicación.
- `barryvdh/laravel-dompdf` no se tocó en `composer.json`; `composer
  update` tampoco cambió su versión instalada.
- `laravel/fortify` y `laravel/socialite` no se tocaron en `composer.json`;
  sus versiones instaladas subieron solo porque `composer update` lo
  exigió dentro de sus propios rangos `^` para satisfacer
  `laravel/framework ^13`.
