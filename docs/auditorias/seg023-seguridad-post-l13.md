# SEG-023: auditoría de seguridad posterior a Laravel 13

Fecha: 2026-09-28  
Agente: Codex  
Rama: `ia/codex/auditoria-seguridad-l13`  
Alcance: revisión estática y comandos de diagnóstico no destructivos. No se
leyó `.env`; no se ejecutaron migraciones, seeders ni backups.

## Resumen ejecutivo

No se encontró una regresión crítica introducida por la migración a Laravel 13.
Los controles críticos corregidos antes del upgrade continúan funcionando y la
suite completa permanece en verde.

Sí se encontró una protección nueva de Laravel 13 que el proyecto no adoptó:
`config/cache.php` omite `serializable_classes => false`. Al no definirla, los
stores serializados reciben `null` y conservan el `unserialize()` permisivo. El
proyecto solo guarda arrays y escalares en caché, por lo que puede adoptar el
valor seguro sin una incompatibilidad visible.

También se confirmó un hueco anterior a la migración: AutoClean no usa
`EnsureAdminToolsEnabled`, aunque su enlace sí se oculta fuera de local. No es
una regresión de Laravel 13 —el historial demuestra que quedó fuera del commit
original que limitó las herramientas—, pero contradice la expectativa general de
que todas las herramientas web administrativas quedan desactivadas.

`composer audit --locked` no reportó vulnerabilidades ni paquetes abandonados.

## Hallazgos

| ID | Severidad | Hallazgo | Evidencia archivo:línea | Corrección propuesta |
|---|---|---|---|---|
| SEG023-001 | Media | **No se adoptó el allowlist de clases serializables de caché de Laravel 13.** La guía oficial incorpora `cache.serializable_classes = false` para mitigar cadenas de gadgets de deserialización. Al faltar la clave, `CacheManager` entrega `null` y los stores recurren a `unserialize($value)` sin `allowed_classes`. No hay una necesidad funcional que lo justifique: la aplicación solo cachea arrays de slugs y valores escalares. | `config/cache.php:106-108`; `app/Console/Commands/ModulesSyncCommand.php:46-47`; `app/Models/Configuracion.php:34`; comportamiento confirmado en `vendor/laravel/framework/src/Illuminate/Cache/CacheManager.php:471-474` y los métodos `unserialize` de los stores. | En una tarea de corrección, añadir `'serializable_classes' => false` al nivel raíz de `config/cache.php`, limpiar caché de configuración y ejecutar la suite. Si en el futuro se necesita cachear objetos, permitir exclusivamente sus clases concretas. |
| SEG023-002 | Media | **AutoClean elude el interruptor local de herramientas administrativas.** Sus rutas están bajo rol Superadmin, pero `CleanController` solo agrega `auth`; no aplica `EnsureAdminToolsEnabled`. Con `admin_tools.enabled=false`, una petición directa todavía puede ejecutar limpieza de caché, rutas, vistas, compilados o autoload. El enlace se oculta, pero eso no es control de acceso. Es un hueco preexistente desde `61351b10`, no una regresión de Laravel 13. | `routes/web.php:374-378`; `app/Http/Controllers/CleanController.php:13-17,37-82`; `app/Http/Middleware/EnsureAdminToolsEnabled.php:11-15`; `config/admin_tools.php:4`; `tests/Feature/AdminWebToolsTest.php:24-32` no incluye AutoClean. | Aplicar `EnsureAdminToolsEnabled` a `CleanController` y agregar sus rutas index/execute a las pruebas negativas de `AdminWebToolsTest`. Mantener además el rol Superadmin y los límites de frecuencia actuales. |
| SEG023-003 | Baja | **Los upgrades sensibles de autenticación carecen de pruebas de flujo.** Fortify/TOTP y Socialite sí se usan, pero la suite solo prueba cifrado del secreto OAuth y acceso al índice; no prueba login/callback OAuth, activación/desafío 2FA ni códigos de recuperación. Los cambios de `pragmarx/google2fa` 8→9, Socialite 5.23→5.31 y Fortify 1.25→1.40 podrían producir una regresión de autenticación que la suite actual no detectaría. No se observó una vulnerabilidad concreta en el código. | `config/fortify.php:146-157`; `app/Http/Controllers/Auth/SocialAuthController.php:50-89`; búsqueda en `tests/` sin casos de 2FA/callback OAuth; `tests/Unit/EncryptedCredentialsTest.php:76-92`. | Antes del despliegue, añadir pruebas de integración de estado OAuth/callback y pruebas Fortify de habilitación, confirmación, login 2FA y recuperación. Mientras dependan de proveedores externos, completar además la lista manual de Fase 3. |
| SEG023-004 | Informativa | **`laravel/passkeys` está instalado pero inactivo y no expone endpoints.** Fortify lo incorporó como dependencia opcional, pero el proyecto no habilita `Features::passkeys()`, `User` no implementa `PasskeyUser` ni usa `PasskeyAuthenticatable`, y `route:list` no contiene rutas `passkey`/`webauthn`. Fortify además llama a `LaravelPasskeys::ignoreRoutes()` y registra sus propias rutas solo al activar la característica. | `config/fortify.php:146-157`; `app/Models/User.php`; salida de `php artisan route:list`; paquete fijado a `laravel/passkeys v0.2.1`. | Ninguna acción urgente. Si no se planea usar passkeys, aceptar que sigue siendo dependencia transitiva de Fortify y vigilar `composer audit`. Si se activa, configurar explícitamente relying party/orígenes, trait/contrato, migración y pruebas antes de desplegar. |
| SEG023-005 | Informativa | **Las versiones criptográficas/HTTP resueltas no presentan avisos conocidos y el código no usa directamente sus APIs incompatibles.** `phpseclib` 4 cambió namespace y excepciones, pero no hay imports `phpseclib3/4` en la aplicación; la versión 4.0.1 contiene la corrección de temporización de Curve25519/448. Guzzle 8.2.0 está detrás del cliente HTTP/Socialite y no hay uso directo de CookieJar, proxy ni APIs eliminadas. | `composer.lock`: `phpseclib/phpseclib 4.0.1`, `guzzlehttp/guzzle 8.2.0`; búsqueda sin `phpseclib`/`GuzzleHttp` en `app/`; `app/Services/GeocodingService.php:83,129,169` usa la fachada HTTP; `composer audit --locked`: cero avisos. | Mantener el lock, ejecutar `composer audit --locked` en CI y probar manualmente OAuth/geocodificación tras cambios futuros de Guzzle o Socialite. |

## Controles previos verificados: intactos/rotos

| Control previo | Estado | Evidencia |
|---|---|---|
| Rol Superadmin en `/superadmin` | **Intacto** | El grupo conserva `auth`, `verified` y `role:Superadmin` en `routes/web.php:311`; las pruebas negativas cubren los índices y acciones sensibles en `tests/Feature/SuperadminAccessTest.php`. |
| `MascotaPolicy` / prevención de IDOR | **Intacto** | `app/Policies/MascotaPolicy.php:13-49` mantiene separación Cliente/Administrador/Paseador; `MascotaController` autoriza operaciones y `tests/Feature/MascotaAccessControlTest.php` cubre lectura, escritura, listado y creación. |
| Cifrado de credenciales en BD | **Intacto** | Los casts `encrypted` y atributos ocultos siguen en `DatabaseConfig.php:28-32`, `EmailConfig.php:30-34`, `BackupConfig.php:32-36` y `OAuthProvider.php:21-26`; `EncryptedCredentialsTest` permanece en verde. |
| `CheckModuleStatus` fail-closed | **Intacto** | Si falta la tabla o una consulta lanza excepción devuelve 503 y no expone el detalle (`app/Http/Middleware/CheckModuleStatus.php:15-51`); las pruebas unitarias cubren ambos casos. |
| Herramientas web fuera de local | **Parcialmente roto, no por Laravel 13** | Configuración BD/correo/backup, migraciones y seeders conservan `EnsureAdminToolsEnabled` y sus pruebas negativas. AutoClean nunca recibió ese middleware; ver SEG023-002. El historial de `61351b10` prueba que la omisión precede al upgrade. |

Ninguno de los cuatro controles intactos sufrió una regresión por Laravel 13.
El estado parcial de AutoClean es deuda preexistente, por lo que no se clasifica
como la regresión crítica indicada en la tarea.

## Revisión de dependencias sensibles

### Laravel 13

- La protección CSRF nueva está activa por el grupo `web`: no hay exclusiones,
  referencias al alias antiguo ni `withoutMiddleware` en el código de la
  aplicación. Laravel 13 agrega verificación de origen mediante
  `Sec-Fetch-Site` y conserva el token como fallback.
- `session.serialization` está configurado en `json`. La única escritura propia
  directa es `last_activity`, un entero; los demás `with()` revisados contienen
  strings, arrays y escalares. No se encontraron objetos propios guardados en
  sesión.
- No hay `upsert`, custom cache stores, rutas por dominio, pivotes polimórficos,
  listeners `JobAttempted`/`QueueBusy` ni fábricas globales `Str` afectados por
  la guía.
- Falta el nuevo endurecimiento de caché descrito en SEG023-001.

Fuente oficial: [Laravel 13 Upgrade Guide](https://laravel.com/framework/docs/13.x/upgrade)
y [Laravel 13 CSRF Protection](https://laravel.com/framework/docs/13.x/csrf).

### Fortify, Socialite y passkeys

- Fortify 1.40 conserva 2FA activo con confirmación y confirmación de contraseña.
- Passkeys se añadió a Fortify como característica opcional. La documentación
  oficial exige `Features::passkeys()`, contrato/trait en `User` y configuración
  de relying party/orígenes; ninguno está presente, de modo que la dependencia
  no se autohabilita.
- Socialite mantiene el flujo con estado (no se llama `stateless()`), lo que
  conserva protección frente a login CSRF. Los providers se restringen a los
  activos/configurados antes de construir el driver.
- El riesgo restante es de regresión no cubierta por pruebas, no una brecha
  demostrada (SEG023-003).

Fuentes oficiales: [Fortify 13.x — Passkeys](https://github.com/laravel/docs/blob/13.x/fortify.md),
[Fortify changelog](https://github.com/laravel/fortify/blob/1.x/CHANGELOG.md) y
[Socialite changelog](https://github.com/laravel/socialite/blob/5.x/CHANGELOG.md).

### Spatie Permission

La aplicación permanece en la serie 6 (`6.25.0`), no hubo salto de major. Los
aliases de middleware se registran en `bootstrap/app.php` y las pruebas de rol
Superadmin, asignación de roles y accesos negativos pasan. No se detectó cambio
de guard, caché o comparación de roles introducido por la actualización.

### phpseclib y Guzzle

`phpseclib 4.0.1` es un salto mayor real: cambió el namespace de `phpseclib3` a
`phpseclib4` y varias operaciones SSH/SFTP ahora lanzan excepciones. La app no
consume esas APIs directamente. La versión resuelta incluye la corrección de
temporización publicada para 4.0.0.

Guzzle 8.2.0 también es un salto mayor real. No hay consumo directo de sus APIs
en la aplicación; Laravel HTTP y Socialite son los consumidores. Los avisos de
seguridad conocidos no aparecen contra el lock actual en Composer Audit.

Fuentes oficiales: [phpseclib changelog](https://github.com/phpseclib/phpseclib/blob/master/CHANGELOG.md),
[phpseclib advisory Curve25519/448](https://github.com/phpseclib/phpseclib/security/advisories/GHSA-q97c-8qh3-fpc6)
y [Guzzle security advisories](https://github.com/guzzle/guzzle/security).

## Verificaciones ejecutadas

- `php artisan route:list`: sin rutas de passkeys/WebAuthn; rutas Fortify de 2FA
  presentes con `auth`, `guest`, `password.confirm` y throttling según operación.
- `composer audit --locked --format=json`: cero advisories, cero abandonados.
- `php artisan test`: 144 pruebas, 436 aserciones, todas correctas.
- `composer validate`: `composer.json` válido.

## Prioridad sugerida

1. Adoptar `cache.serializable_classes = false` (SEG023-001).
2. Cerrar el acceso directo a AutoClean fuera de local y probarlo (SEG023-002).
3. Añadir cobertura de OAuth/2FA antes del despliegue (SEG023-003).

Los puntos 1 y 2 requieren cambios fuera del alcance de esta auditoría de solo
lectura; no se modificó código, configuración ni dependencias.
