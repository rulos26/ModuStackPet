# Fase 1 — Resultado de actualización de dependencias

> **Nota (2026-09-27, tarea 003):** este documento describe primero el
> resultado original de la tarea 002 (rama
> `ia/claude/fase1-actualizar-dependencias`, unida a `main` en `9cb3f22c`).
> Esa actualización usó `composer update` sin `--with-dependencies` acotado,
> lo que arrastró saltos de versión **mayor** en dependencias indirectas
> (`phpseclib` 3→4, `google2fa` 8→9, `php-jwt` 6→7, `php-css-parser` 8→9,
> `webmozart/assert` 1→2), violando la restricción de la tarea 002 de "ningún
> salto de versión mayor". La sección
> [**Corrección dirigida (tarea 003)**](#corrección-dirigida-tarea-003-rama-iaclaudefase1-dirigida)
> al final de este documento describe la actualización rehecha que corrige
> ese problema. **Esa sección es el estado vigente**; lo anterior queda como
> registro histórico.

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

---

## Corrección dirigida (tarea 003), rama `ia/claude/fase1-dirigida`

Fecha: 2026-09-27.

### Motivo
El PR #4 (`9cb3f22c`) unió la rama de la tarea 002, que introdujo saltos de
versión **mayor** en dependencias indirectas: `phpseclib/phpseclib` 3→4,
`pragmarx/google2fa` 8→9, `firebase/php-jwt` 6→7, `sabberworm/php-css-parser`
8→9 y `webmozart/assert` 1→2. Esto violaba la restricción explícita de la
tarea 002 ("prohibido... cualquier paquete a una versión mayor"), porque se
usó `composer update` sin acotar a los paquetes vulnerables.

### Qué se hizo
1. Restaurado `composer.lock` (y `composer.json`/`phpstan.neon`) al estado
   previo al merge: `git checkout 9cb3f22c^1 -- composer.lock composer.json phpstan.neon`.
2. `composer audit --locked` ANTES (sobre ese estado restaurado): **52 avisos
   / 15 paquetes** (igual que en la tarea 002 original).
3. Reemplazo de `nunomaduro/larastan` por `larastan/larastan` ^3.12
   (`composer remove` + `composer require --no-blocking`) y actualización de
   `phpstan.neon`.
4. `composer update` **acotado** con `--with-dependencies --no-blocking`
   (sin `-W`/`--with-all-dependencies`) sobre exactamente la lista pedida:
   `laravel/framework dompdf/dompdf barryvdh/laravel-dompdf guzzlehttp/guzzle
   guzzlehttp/psr7 league/commonmark phpseclib/phpseclib symfony/* phpunit/phpunit
   psy/psysh`.
   - `--no-blocking` fue necesario porque Composer 2.10 bloquea por defecto
     la resolución hacia cualquier versión de `laravel/framework` 11.x, ya
     que toda la línea 11.31–11.56 tiene al menos un aviso sin parche en la
     rama 11.x (el fix real está en 12.x/13.x).
5. Ese `update` acotado igualmente arrastró `sabberworm/php-css-parser` de
   v8.8.0 a **v9.5.0** (salto mayor) porque `dompdf/php-svg-lib` acepta
   `^8.4 || ^9.0` y Composer eligió la más nueva. Se corrigió fijando
   temporalmente `sabberworm/php-css-parser: ^8.9` en `composer.json`,
   ejecutando `composer update sabberworm/php-css-parser --with-dependencies
   --no-blocking` (queda en **v8.9.0**, la última 8.x), y luego quitando esa
   línea de `composer.json` con `composer update --lock` (solo recalcula el
   hash del lock, sin volver a resolver versiones) para no dejar una
   dependencia directa espuria.
6. Verificación explícita de saltos de versión mayor: se comparó cada
   paquete del `composer.lock` resultante contra `9cb3f22c^1` (script PHP
   ad-hoc). Resultado: **ningún paquete cambió de versión mayor**, salvo el
   reemplazo intencional `nunomaduro/larastan` → `larastan/larastan`.
   - `phpseclib/phpseclib`: 3.0.47 → **3.0.57** (se queda en 3.x).
   - `pragmarx/google2fa`: no tocado, sigue en **v8.0.3**.
   - `firebase/php-jwt`: no tocado, sigue en **v6.11.1**.
   - `sabberworm/php-css-parser`: 8.8.0 → **8.9.0** (se queda en 8.x).
   - `webmozart/assert`: 1.11.0 → **eliminado** (ya no lo requiere
     `dragonmantank/cron-expression` 3.6.0; no es un salto de versión, es una
     dependencia que dejó de ser necesaria).
   - `laravel/fortify` y `laravel/socialite`: **no tocados** (restricción
     explícita de la tarea 003), siguen en v1.25.4 y v5.23.1.
   - `laravel/framework`: se queda en **v11.56.1** dentro de `^11.31` (sin
     cambio de versión mayor).

### Avisos DESPUÉS (`composer audit --locked`)
**4 avisos en 2 paquetes:**

| Paquete | Severidad | Advisory | Motivo por el que queda |
|---|---|---|---|
| `firebase/php-jwt` v6.11.1 | baja | PKSA-y2cr-5h3j-g3ys (CVE-2025-45769) | El fix requiere `^7.0` (salto mayor). Es dependencia de `laravel/socialite`, que la tarea 003 prohíbe explícitamente actualizar. |
| `laravel/framework` v11.56.1 | media | PKSA-m5cs-t1y6-qpcs | Fix en `^12.61.1`/`^13.12.0` (Fase 2). |
| `laravel/framework` v11.56.1 | alta | PKSA-3r5d-mb8f-1qw9 | Fix en `^12.60.0`/`^13.9.0+` (Fase 2). |
| `laravel/framework` v11.56.1 | — | PKSA-mdq4-51ck-6kdq (CVE-2026-48019) | Afecta toda la rama 9.x–13.9.x; fix definitivo en 12+ (Fase 2). |

Nota: esto es **un aviso más** que en el resultado original de la tarea 002
(3 en lugar de 4), precisamente porque ahí sí se dejó pasar el salto mayor de
`firebase/php-jwt` a 7.x, que corregía ese aviso. Aquí se prioriza la
restricción de "ningún salto mayor" y "no tocar socialite" sobre cerrar este
aviso de severidad baja.

### Pruebas
`phpunit.xml` ya usa `sqlite`/`:memory:` y un `APP_KEY` fijo (commit
`6d97b116`), así que no fue necesario crear ni tocar `.env`.

- **Antes** (estado restaurado a `9cb3f22c^1`, con vendor reinstalado):
  `27 failed, 4 warnings, 1 passed (13 assertions)`.
- **Después** (con la actualización dirigida aplicada):
  `27 failed, 4 warnings, 1 passed (13 assertions)` — **idéntico**.

Las fallas son `QueryException: SQLSTATE[23000] FOREIGN KEY constraint
failed` al insertar en `module_logs` con `user_id = 0` (dato de prueba sin el
usuario correspondiente); es un problema preexistente de los tests/seeders,
no relacionado con esta actualización de dependencias. No hubo regresiones.

`composer validate`: OK. `php ./vendor/bin/phpstan analyse` carga
`larastan/larastan` y ejecuta el análisis (mismos ~1000+ hallazgos
preexistentes de nivel 10, sin relación con este cambio).

### Confirmación explícita
**No hay ningún salto de versión mayor**, directo ni indirecto, en el
`composer.lock` resultante respecto a `9cb3f22c^1`, con la única excepción
intencional del reemplazo de `nunomaduro/larastan` (abandonado) por
`larastan/larastan`.
