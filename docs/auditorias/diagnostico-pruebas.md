# Diagnóstico y corrección de las pruebas

**Fecha:** 2026-09-27

**Runtime:** PHP 8.2.34 (Laravel Herd), PHPUnit 11.5.56, SQLite en memoria.

No se leyó, creó ni modificó `.env`. La ejecución usó exclusivamente las variables ya definidas en `phpunit.xml:21-32`.

## Resultado

| Momento | Comando | Resultado |
|---|---|---|
| Antes | `php82.bat artisan test` | **27 failed, 4 warnings, 1 passed (13 assertions)** |
| Después | `php82.bat artisan test` | **1 failed, 30 warnings, 1 passed (68 assertions)** |
| Validación | `php82.bat composer.phar validate` | `./composer.json is valid` |

Las 30 pruebas que ya no fallan aparecen como warnings por una única advertencia repetida durante el bootstrap de Laravel: PHPUnit captura el intento suprimido de `vlucas/phpdotenv` de abrir el `.env` inexistente. La prueba unitaria pura no inicia Laravel y queda como `PASS`. El único fallo funcional restante pertenece a código de `app/`, que la tarea prohíbe modificar.

## Causas raíz

| Causa | Pruebas afectadas | Tipo | Corrección aplicada o propuesta |
|---|---|---|---|
| Las factories de `Module` disparaban `ModuleObserver::created`; sin sesión autenticada, el observer usaba `user_id = 0`, inexistente, y violaba la FK. | 4 de `CheckModuleStatusMiddlewareTest`, 11 de `ModuleTest` y 6 de `ModuleManagementTest` fallaban antes de alcanzar sus aserciones. | **Error de prueba/fixture**, originado por un defecto adicional de aplicación. No es diferencia SQLite/MySQL: ambos motores rechazan una FK a un usuario inexistente. | Aplicada: las fixtures que no prueban observers usan `createQuietly()` (`tests/Unit/CheckModuleStatusMiddlewareTest.php:33,51,88,109`; `tests/Unit/ModuleTest.php:32,55-56,68,92,112-223`; `tests/Feature/ModuleManagementTest.php:63-186`). La prueba del slug autentica un usuario real antes de crear el módulo (`ModuleTest.php:41`). Propuesta para aplicación: reemplazar los seis `auth()->id() ?? 0` de `app/Observers/ModuleObserver.php:25-122` por `auth()->id()` y admitir `null` en el logger. |
| Tres pruebas invocaban directamente `DatabaseConfig::updateEnvVariable()`, que es `protected`. | `test_update_env_variable_replaces_existing_variable`, `...handles_commented_variables`, `...adds_new_variable`. | **Error de prueba.** | Aplicada: helper de reflexión `invokeProtected()` en `tests/Unit/DatabaseConfigEnvUpdateTest.php:77`, usado en líneas 22, 56 y 71. También reutilizado para `escapeEnvValue`. |
| La prueba de ruta raíz esperaba HTTP 200, pero la ruta redirige explícitamente a invitados hacia `login`. | `Feature\ExampleTest`. | **Error de prueba.** | Aplicada: se verifica el redirect a `route('login')` en `tests/Feature/ExampleTest.php:17`. |
| La prueba esperaba que un módulo inexistente fuera bloqueado; el middleware vigente lo autocrea activo y permite continuar. | `middleware_blocks_access_to_nonexistent_module`. | **Error de prueba desactualizada.** | Aplicada: renombrada a `middleware_auto_creates_and_allows_nonexistent_module`; verifica HTTP 200 y la fila autocreada (`tests/Unit/CheckModuleStatusMiddlewareTest.php:67-82`). |
| La prueba buscaba el texto técnico `access-denied`, que no forma parte del HTML renderizado. | `middleware_blocks_access_to_inactive_module`. | **Error de prueba.** | Aplicada: verifica el texto visible `Acceso denegado` (`tests/Unit/CheckModuleStatusMiddlewareTest.php:63`). |
| La prueba de relaciones accedía a `$log->log`, pero la relación del modelo se llama `module`. | `module_log_has_relationships`. | **Error de prueba.** | Aplicada: `$log->module` en `tests/Unit/ModuleTest.php:103-105`. |
| Los métodos usaban metadata `@test` en doc-comments, obsoleta en PHPUnit 11 y eliminada en PHPUnit 12. | 26 métodos de `CheckModuleStatusMiddlewareTest`, `ModuleTest` y `ModuleManagementTest`. | **Error de compatibilidad de pruebas.** | Aplicada: atributos `#[Test]` y `PHPUnit\Framework\Attributes\Test`. No se desactivó ninguna prueba ni se eliminó ninguna aserción. |
| Usuario anónimo: el middleware pasa `null`, pero `ModuleLog::createLog()` declara `int $userId`, aunque la migración permite `NULL`. | Solo `middleware_handles_unauthenticated_user` después de corregir las fixtures. | **Error del código de aplicación.** | No aplicado por restricción. Propuesta: cambiar `app/Models/ModuleLog.php:56` a `?int $userId` y conservar el `null` enviado desde `app/Http/Middleware/CheckModuleStatus.php:64-65`. Es consistente con `database/migrations/2025_10_29_150002_create_module_logs_table.php:14` y con la intención de auditar accesos anónimos. |
| PHPUnit informa el warning suprimido de Dotenv al no existir `.env`. No falta `APP_KEY`: ya está definida en `phpunit.xml:25`; SQLite también está configurado en líneas 26-27. | Inicialmente se veía en las 4 pruebas que alcanzaban estado no fallido; después aparece en las 30 pruebas Laravel que ya pasan. | **Configuración del runner**, no fallo de prueba ni diferencia SQLite/MySQL. | No aplicado por restricción. Propuesta en `phpunit.xml`: cambiar `<source>` por `<source restrictWarnings="true">`. PHPUnit 11 admite el atributo (`vendor/phpunit/phpunit/phpunit.xsd:30`) y así limita warnings al código incluido en `app/`, sin crear `.env` ni ocultar warnings propios. |

## Cambios realizados

- Se aislaron las fixtures del observer cuando el observer no forma parte del comportamiento bajo prueba.
- Se corrigieron las expectativas de ruta, middleware y relación Eloquent para reflejar el comportamiento vigente.
- Se encapsuló el acceso por reflexión a los métodos protegidos de `DatabaseConfig`.
- Se migró metadata PHPUnit de doc-comments a atributos.
- No se modificaron factories porque sus datos por defecto eran válidos; el efecto lateral provenía del observer global.

## Pendientes fuera del alcance (de este documento; aplicados en la tarea 005)

1. Aplicar la firma nullable de `ModuleLog::createLog()` y retirar los fallbacks `user_id = 0` del observer. Con ello debe desaparecer el único fallo restante.
2. Añadir `restrictWarnings="true"` al elemento `<source>` de `phpunit.xml` y repetir la suite. Esto evita que la ausencia intencional de `.env` convierta pruebas exitosas en warnings, manteniendo visibles los warnings generados por `app/`.
3. Tras esos dos cambios, el resultado esperado es **32 passed**. Debe confirmarse ejecutando la herramienta oficial; no se afirma como resultado observado.

## Verificaciones ejecutadas

- `php artisan test` mediante PHP 8.2 de Laravel Herd, antes y después.
- `composer validate` mediante Composer de Laravel Herd: válido.
- `php -l` en los cinco archivos de prueba modificados: sin errores de sintaxis.
- `git diff --check`: sin errores de whitespace.

---

## Corrección aplicada (tarea 005), rama `ia/claude/modulelog-env-testing`

Fecha: 2026-09-27. Ambas propuestas de la sección "Pendientes fuera del
alcance" fueron aprobadas por el humano y se aplicaron en esta tarea.

### 1. `ModuleLog::createLog()` con usuario anónimo

- `app/Models/ModuleLog.php:56`: el parámetro `int $userId` pasó a
  `?int $userId` (misma posición).
- Se confirmó en la migración
  `database/migrations/2025_10_29_150002_create_module_logs_table.php:14`
  que `user_id` ya admite `NULL` (`->nullable()->constrained('users')->nullOnDelete()`).
  No fue necesario tocar migraciones.
- `grep -rn "createLog" app/` mostró 6 llamadas adicionales a las de
  `app/Models/ModuleLog.php` con `auth()->id() ?? 0` o `Auth::id() ?? 0`
  como *fallback* de "sin usuario". El paso 1 de la tarea pide
  explícitamente corregir "cualquier uso de 0... por null" en todo `app/`,
  aunque el campo `archivos:` de la tarea solo listaba
  `app/Models/ModuleLog.php`. Se interpretó la instrucción del cuerpo de la
  tarea (más específica y coincidente con la propuesta ya aprobada en este
  mismo diagnóstico) como la vigente, y se corrigieron también:
  - `app/Observers/ModuleObserver.php:25,45,62,83,103,122` — los 6 `auth()->id() ?? 0` → `auth()->id()`.
  - `app/Http/Controllers/RoleAssignmentController.php:40` — `auth()->id() ?? 0` → `auth()->id()`.
  - `app/Http/Controllers/SeederController.php:118` — `Auth::id() ?? 0` → `Auth::id()`.
    (La línea 119, `optional(...)->id ?? 0`, es el `moduleId`, no el
    usuario; `createLog()` no cambió esa firma, así que se dejó intacta.)
  - Se revisaron el resto de llamadores (`ModuleController.php`,
    `CheckModuleStatus.php`, `ToggleButton.php`) y ninguno usaba `?? 0`.
- En los tests, se revirtió `Module::factory()->createQuietly(...)` a
  `Module::factory()->create(...)` en los 22 usos de:
  - `tests/Feature/ModuleManagementTest.php`
  - `tests/Unit/CheckModuleStatusMiddlewareTest.php`
  - `tests/Unit/ModuleTest.php`

  Se revisó cada prueba antes de revertir: ninguna verifica un conteo exacto
  de filas de `module_logs` (todas usan `assertDatabaseHas`, que no se ve
  afectado por filas adicionales del observer), así que no fue necesario
  mantener `createQuietly()` en ningún caso.

### 2. `.env.testing` sin secretos

- Creado [.env.testing](../../.env.testing) con solo comentarios; ninguna
  variable ni secreto. Laravel lo detecta automáticamente porque
  `phpunit.xml` define `APP_ENV=testing`
  (`Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::checkForSpecificEnvironmentFile()`
  busca `.env.{APP_ENV}` antes de intentar `.env`), así que Dotenv deja de
  intentar abrir un `.env` inexistente y las advertencias desaparecen.
- `git check-ignore -v .env.testing` no devolvió nada (no está ignorado);
  no fue necesario tocar `.gitignore`.
- Añadida a `AGENTS.md` (sección Seguridad) la nota: "`.env.testing` es la
  única excepción a la regla de `.env`: se versiona, y nunca debe contener
  secretos."

### Resultado

| Momento | Comando | Resultado |
|---|---|---|
| Antes (estado de este diagnóstico) | `php artisan test` | 27 failed, 4 warnings, 1 passed |
| Después de corregir `ModuleLog`/observer/tests (sin `.env.testing`) | `php artisan test` | 0 failed, 31 warnings, 1 passed |
| Después de agregar `.env.testing` | `php artisan test` | **32 passed, 0 failed, 0 warnings** |

`composer validate`: OK. `php -l` sin errores en los 8 archivos modificados
(`app/Models/ModuleLog.php`, `app/Observers/ModuleObserver.php`,
`app/Http/Controllers/RoleAssignmentController.php`,
`app/Http/Controllers/SeederController.php`, y los 3 archivos de tests).

No se tocó `.env`, ninguna migración, ninguna dependencia, ni ningún otro
archivo de `app/` fuera de los listados arriba.
