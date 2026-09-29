# SEG-024: auditoría de consistencia y pendientes aplazados

Fecha: 2026-09-28  
Agente: Cursor  
Rama: `ia/cursor/auditoria-consistencia-pendientes`  
Alcance: **solo lectura** (sin cambios de código, configuración ni dependencias).

## Resumen ejecutivo

Los pendientes congelados (rotación de credenciales y `TokenSeeder`) siguen
como se documentó. Los tres pares de modelos duplicados no se consolidaron.
Hay debt de consistencia (auth duplicado, 2FA Fortify incompleto, TODOs
funcionales), cobertura de pruebas concentrada en seguridad/mascotas/módulos
y **cero CI** en el repositorio. Prioridad alta recomendada: CI mínimo +
pruebas de Socialite; la consolidación de modelos y la limpieza de auth
pueden esperar a tareas dedicadas.

---

## 1. Pendientes aplazados (congelados)

### 1.1 Rotación de credenciales

**Estado:** sigue CONGELADO según
`docs/auditorias/pendiente-rotacion-credenciales.md` (decisión humana
2026-09-28). No se rotó nada en esta auditoría.

**Evidencia de que el congelamiento se respeta en el árbol actual:**

- `.env.example:92` tiene `MAIL_PASSWORD=` vacío (sin secreto).
- No hay `GOOGLE_CLIENT_*` ni `FACEBOOK_CLIENT_*` en `.env.example` (búsqueda
  sin coincidencias).
- La decisión original sigue vigente: aplazar hasta reevaluar el ingreso por
  redes sociales.

**Cambio de contexto desde el congelamiento:**

- La Fase 3 subió `laravel/socialite` y `laravel/fortify` (y transitivas
  `firebase/php-jwt`, `pragmarx/google2fa`) — ver
  `docs/auditorias/fase3-laravel13.md` § “Funciones a probar manualmente”.
  Eso refuerza, no debilita, la premisa de “esperar a rediseñar login social”
  antes de regenerar secretos OAuth.
- `.env.example:91` y `:94` aún exponen el correo real
  `rulos26@gmail.com` como `MAIL_USERNAME` / `MAIL_FROM_ADDRESS` (no es el
  secreto, pero es dato personal en un archivo versionado).
- Los secretos históricos siguen potencialmente en el historial de git
  (estado documentado: corregido en HEAD, expuesto en historial).

| ID | Severidad | Hallazgo | Evidencia | Recomendación |
|----|-----------|----------|-----------|---------------|
| C-001 | Alta (cuando se retome) | Rotación aún pendiente; historial sigue siendo el riesgo | `pendiente-rotacion-credenciales.md`; historial git | Mantener congelado hasta decisión de login social; al retomar, seguir checklist del doc. Prioridad **alta** en ese momento. |
| C-002 | Baja | Email real en `.env.example` | `.env.example:91,94` | Sustituir por placeholder (`mail@example.com`). Prioridad **baja**. |

### 1.2 TokenSeeder

**Estado:** sigue CONGELADO según decisión humana en
`docs/auditorias/seg018-tokenseeder-investigacion.md` (reevaluar tras
versión 3.0 estable; si no se usó, eliminar).

**Evidencia de que sigue congelado / inerte:**

- `database/seeders/TokenSeeder.php:15-18` — guard `local`/`testing` intacto.
- `TokenSeeder.php:5` — sigue importando `App\Models\AdminDashboard\Token`
  (clase inexistente).
- `database/seeders/DatabaseSeeder.php:23` —
  `// $this->call(TokenSeeder::class);` sigue comentado.
- `database/seeders/ExecuteSqlSeeder.php:28-49` — bloque comentado que incluye
  `token.sql` (línea 37).
- `database/sql/token.sql` y `TokenSeeder.php` siguen en el árbol.
- `database/seeders/roleSeeder.php:153-157` — permisos `tokens.*` siguen
  creándose.
- Prueba de no-ejecución en producción:
  `tests/Feature/CrearSuperadminCommandTest.php` →
  `test_token_seeder_no_hace_nada_en_production`.

**Cambio de contexto:** ninguno que inactive el congelamiento. No hay
versión 3.0 de producto marcada; Laravel 13 ya está en el framework
(`composer.json:12` `^13.0`), pero la decisión habla de “versión 3.0
estable” del producto, no del major de Laravel.

| ID | Severidad | Hallazgo | Evidencia | Recomendación |
|----|-----------|----------|-----------|---------------|
| C-003 | Media | Código muerto + permisos/módulo huérfanos | `TokenSeeder.php`; `roleSeeder.php:153-157`; SEG-018 | Mantener congelado hasta 3.0; luego eliminar seeder, SQL y llamadas; confirmar si se retiran permisos `tokens.*`. Prioridad **media**. |

---

## 2. Modelos duplicados (sin consolidar)

Confirmado: los tres pares de SEG-021 **siguen sin consolidar**.

| Par | Tabla | Canónico (dominio) | CRUD / generado | SoftDeletes |
|-----|-------|--------------------|-----------------|-------------|
| `Ciudad` / `Ciudade` | `ciudades` | `Ciudad` (`EmpresaController`, relaciones) | `CiudadController` → `Ciudade` (`CiudadController.php:5`) | Solo `Ciudad`; `Ciudade` no (`Ciudade.php:29`) |
| `Sector` / `Sectore` | `sectores` | `Sector` | `SectoreController` → `Sectore` | Solo `Sector`; `Sectore` no |
| `TipoEmpresa` / `TiposEmpresa` | `tipos_empresas` | `TipoEmpresa` | `TiposEmpresaController` → `TiposEmpresa` | Solo `TipoEmpresa`; `TiposEmpresa` no |

**Esfuerzo estimado:** medio (1–2 días con pruebas). Pasos: (1) pruebas de
caracterización de los tres CRUD + relaciones Empresa/Cliente/Paseador/
Departamento; (2) sustituir consumidores; (3) verificar soft delete e
`id_municipio`; (4) retirar clases generadas.

**Riesgo de hacerlo ahora:**

- Alto en comportamiento: el CRUD actual hace borrado físico; al pasar al
  modelo canónico pasa a soft delete (cambio observable).
- Medio en binding/rutas: `ciudades/{ciudad}` hoy resuelve `Ciudade` con
  PK `id_municipio`.
- Conflicto de archivos: tarea 023 (Codex) también declara
  `archivos: [docs/auditorias/]`; la consolidación de código no debería
  solaparse, pero conviene una tarea propia con `archivos:` de modelos y
  controladores.

| ID | Severidad | Hallazgo | Evidencia | Recomendación |
|----|-----------|----------|-----------|---------------|
| C-004 | Media | Tres pares activos sin consolidar; SoftDeletes divergente | SEG-021; `CiudadController.php:5`; `SectoreController.php:5`; `TiposEmpresaController.php:5` | No consolidar “de paso”. Tarea dedicada con pruebas previas. Prioridad **media**. |

---

## 3. Consistencia general del código

### 3.1 Tabla vs modelo (estilo Paseador)

- `Paseador` ya declara `protected $table = 'paseadores'`
  (`app/Models/Paseador.php:10`); cubierto por `tests/Unit/PaseadorTest.php`.
- `VacunasCertificacione` **no** declara `$table`, pero Eloquent resuelve
  `vacunas_certificaciones` (verificado en runtime con `getTable()`). Nombre
  de clase incorrecto en español (debería ser singular/canónico), no bug de
  tabla hoy.
- `Departamento` importa `SoftDeletes` (`Departamento.php:7`) pero **no** lo
  usa en el `use` del trait (solo `HasFactory`, línea 25): import muerto /
  soft delete incompleto respecto a la migración.

### 3.2 Inglés / español mezclado

- Rutas y recursos: `document-requirements`, `mascota-documents`,
  `oauth-providers`, `database-configs` (inglés) junto a `mascotas`,
  `ciudades`, `empresas`, `usuarios` (español) — `routes/web.php`.
- Modelos: `DocumentRequirement`, `MascotaDocument`, `OAuthProvider` vs
  `MensajeDeBienvenida`, `TipoDocumento`, `VacunasCertificacione`.
- Controladores duplicados de auth (ver abajo).

### 3.3 Código muerto / duplicado / comentado

- **Auth duplicado:** existen
  `app/Http/Controllers/RegisterController.php` y
  `app/Http/Controllers/Auth/RegisterController.php`; igual para
  `ResetPasswordController` y `LoginController` (raíz vs `Auth\`).
  `routes/web.php` usa `RegisterController` (raíz, `:10`),
  `ResetPasswordController` (raíz, `:11`) y `Auth\LoginController` (`:32`).
  Las variantes no referenciadas son candidatas a código muerto.
- **Registro duplicado en rutas:** `web.php:91-96` registra
  `RegisteredUserController` (Fortify/Breeze) y luego otra vez
  `RegisterController` con el **mismo** nombre de ruta `register` — la
  segunda definición gana; la primera es ruido.
- **`App\Providers\FortifyServiceProvider`:** existe y configura acciones,
  vistas y rate limiters (`FortifyServiceProvider.php:18-74`), pero **no**
  está en `bootstrap/providers.php` (solo `AppServiceProvider` y
  `ViewServiceProvider`). Solo se auto-descubre
  `Laravel\Fortify\FortifyServiceProvider` del paquete. La customización
  de la app probablemente no se ejecuta.
- `User` declara `use Notifiable` y `use HasRoles` **duplicados**
  (`User.php:16-19`).
- `routes/web.php:130-131` — bloque comentado de rutas admin antiguas.

### 3.4 TODOs en código de aplicación

| Ubicación | Texto |
|-----------|--------|
| `app/Models/DocumentRequirement.php:90` | `TODO: Implementar lógica de razas peligrosas` — el método `aplicaParaRaza` siempre retorna `true`. |
| `app/Services/DocumentValidationService.php:197-200` | `TODO: Implementar validación OCR...` — validaciones de firma/sello no implementadas. |

| ID | Severidad | Hallazgo | Evidencia | Recomendación |
|----|-----------|----------|-----------|---------------|
| C-005 | Alta | Auth/registro solapado (Fortify + controladores custom + ruta `register` duplicada); FortifyServiceProvider de app no registrado | `routes/web.php:91-96`; `bootstrap/providers.php`; `FortifyServiceProvider.php` | Unificar un solo stack de auth; registrar o eliminar el provider de app. Prioridad **alta**. |
| C-006 | Media | `aplicaParaRaza` no filtra razas peligrosas | `DocumentRequirement.php:80-90` | Implementar o documentar que el flag no tiene efecto. Prioridad **media**. |
| C-007 | Baja | Nombre `VacunasCertificacione`; import SoftDeletes muerto en Departamento; traits duplicados en User | modelos citados | Limpieza cosmética en tarea de tidy. Prioridad **baja**. |
| C-008 | Baja | Controladores Auth duplicados sin uso | `Auth/RegisterController.php` etc. | Mover a `_borrar/` tras confirmar con `rg`. Prioridad **baja**. |

---

## 4. Cobertura de pruebas (lista concreta)

Suite actual bajo `tests/` (19 archivos PHP contando `TestCase.php`).
Áreas **con** cobertura relevante:

| Área | Archivo(s) de prueba |
|------|----------------------|
| Portada / guest | `Feature/ExampleTest.php` |
| Mascotas (CRUD + IDOR + paseador) | `MascotaFlowsTest`, `MascotaAccessControlTest` |
| Documentos de mascota | `MascotaDocumentFlowsTest` |
| Módulos + middleware fail-closed | `ModuleManagementTest`, `CheckModuleStatusMiddlewareTest`, `Unit/ModuleTest` |
| Superadmin (rutas índice, backups, migraciones, DB config, usuarios store) | `SuperadminAccessTest` |
| Resource usuarios Superadmin | `SuperadminUsuariosResourceAccessTest` |
| Asignación de roles | `RoleAssignmentAccessTest` |
| Herramientas web admin desactivadas | `AdminWebToolsTest` |
| Session timeout | `SessionTimeoutTest` |
| Comando crear-superadmin + seeders en production | `CrearSuperadminCommandTest` |
| Cifrado credenciales / migración | `EncryptedCredentialsTest`, `EncryptCredentialsMigrationTest` |
| `DatabaseConfig` update env | `DatabaseConfigEnvUpdateTest` |
| Modelo `Paseador` tabla | `Unit/PaseadorTest` |

### Controladores / áreas **sin** prueba automatizada dedicada

Lista de controladores de aplicación sin Feature test que ejercite sus
rutas principales (excluido el abstracto `Controller`):

1. `Auth/SocialAuthController` — OAuth redirect/callback  
2. `Auth/RegisterController` y `RegisterController` — registro  
3. `Auth/LoginController` / `LoginController` — login exitoso/fallido (solo
   redirects a login en otras pruebas)  
4. `ResetPasswordController` / `Auth/ResetPasswordController` /
   `ForgotPasswordController` — reset de contraseña  
5. `VerificationController` — verificación email (hay rutas closure en
   `web.php`, sin test)  
6. `LogoutController`  
7. `ClienteController` — dashboard/perfil cliente  
8. `PaseadorController` — dashboard/perfil paseador  
9. `AdminController` — dashboard y CRUD `admin/users`  
10. `CiudadController`  
11. `SectoreController`  
12. `TiposEmpresaController`  
13. `EmpresaController` (incluye PDF empresa)  
14. `DepartamentoController`  
15. `BarrioController`  
16. `RazaController`  
17. `VacunasCertificacionesController`  
18. `DocumentRequirementController`  
19. `PathDocumentoController`  
20. `MensajeDeBienvenidaController`  
21. `TipoDocumentoController`  
22. `ConfiguracionController`  
23. `PDFController` — PDF mascota  
24. `ArbolGenealogicoController`  
25. `ProfileController`  
26. `Superadmin/OAuthProviderController` — CRUD/test/simulate providers  
27. `Superadmin/EmailConfigController` — más allá del “store bloqueado”
    en AdminWebTools  
28. Flujos Fortify 2FA (no hay controlador propio; tampoco hay tests)

| ID | Severidad | Hallazgo | Evidencia | Recomendación |
|----|-----------|----------|-----------|---------------|
| C-009 | Alta | ~25 controladores/áreas sin Feature test; auth social y catálogos enteros sin red de seguridad | listado arriba; `tests/Feature/*` | Priorizar SocialAuth + login/registro + un CRUD catálogo canónico. Prioridad **alta**. |

---

## 5. Deuda de infraestructura (CI)

**Hallazgo:** no existe `.github/` en el repositorio del proyecto (solo
workflows dentro de `vendor/`). No hay GitHub Actions, ni otro CI
versionado (`docker-compose.yml` es entorno local, no CI).

**Qué implicaría un workflow mínimo:**

```yaml
# Esbozo (no aplicado — solo lectura)
on: [push, pull_request]
jobs:
  tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'   # composer.json exige ^8.3
          extensions: mbstring, sqlite, pdo_sqlite
          coverage: none
      - run: composer install --no-interaction --prefer-dist
      - run: composer validate --no-check-publish
      - run: php artisan test
```

**Facilitadores ya presentes:**

- `phpunit.xml` define `APP_KEY`, `DB_CONNECTION=sqlite`,
  `DB_DATABASE=:memory:` — **no requiere `.env`** ni secretos en CI.
- Suite reportada en Fase 3: 144 pruebas en verde tras Laravel 13.

**Coste:** bajo (½–1 día: workflow + badge + arreglar cualquier flaky en
CI limpio). Riesgo bajo si se evita `composer update` y se usa el lock.

| ID | Severidad | Hallazgo | Evidencia | Recomendación |
|----|-----------|----------|-----------|---------------|
| C-010 | Alta | Sin CI que ejecute `php artisan test` en cada push/PR | ausencia de `.github/workflows/` | Añadir workflow mínimo (PHP 8.3 + composer install + test + validate). Prioridad **alta**. |

---

## 6. Login social (Socialite) y 2FA (Fortify)

### Confirmación: sin pruebas automatizadas

- Búsqueda en `tests/` de `Socialite`, `Fortify`, `twoFactor`, `2FA`,
  `social.redirect`, `SocialAuth`: **sin coincidencias de flujo** (solo
  `OAuthProvider` en test de cifrado de `client_secret`).
- `fase3-laravel13.md` ya listó estas áreas como “probar manualmente”
  tras subidas mayores de dependencia; el vacío de tests **persiste**.

### Socialite — escenarios que deberían cubrirse

Rutas: `web.php:117-118` → `SocialAuthController::redirect|callback`.

1. Proveedor inactivo / desconocido → rechazo controlado.  
2. Redirect genera URL OAuth (mock de `Socialite::driver`).  
3. Callback usuario nuevo → crea `User` + `SocialAccount` + perfil
   `Cliente`.  
4. Callback usuario existente mismo email → vincula cuenta sin duplicar.  
5. Callback con error del proveedor → no autentica; mensaje genérico.  
6. Flujo `test_session_id` / `state=test_*` del simulador Superadmin
   escribe `OAuthTestLog` y no deja sesión de producción sucia.  
7. Credenciales del provider se leen cifradas desde `oauth_providers`
   (ya hay unit de cifrado; falta integración del callback).

### Fortify / 2FA — estado real y escenarios

Evidencia de **configuración incompleta** respecto a lo que sugiere
`config/fortify.php`:

- `config/fortify.php:152-156` habilita
  `Features::twoFactorAuthentication([...])`.
- `app/Models/User.php` **no** usa `TwoFactorAuthenticatable` (búsqueda
  `TwoFactor` en `app/` vacía).
- No hay migración con columnas `two_factor_*` en `database/migrations/`.
- No hay vistas `two-factor` bajo `resources/`.
- `App\Providers\FortifyServiceProvider` define rate limiter `two-factor`
  pero el provider **no está registrado** (punto 3).

Escenarios a cubrir **cuando** 2FA quede realmente cableado:

1. Habilitar 2FA (QR / secret) y confirmar código.  
2. Login con contraseña + challenge TOTP válido / inválido.  
3. Códigos de recuperación: usar uno, invalidarlo, agotarlos.  
4. Deshabilitar 2FA con contraseña.  
5. Throttle del rate limiter `two-factor`.

Mientras tanto, el hallazgo no es “faltan tests de 2FA”, sino “2FA está
declarado en config pero no implementado en el modelo/esquema”.

| ID | Severidad | Hallazgo | Evidencia | Recomendación |
|----|-----------|----------|-----------|---------------|
| C-011 | Alta | Socialite sin Feature tests tras bump mayor | `SocialAuthController.php`; `fase3-laravel13.md` | Añadir tests con Socialite fake/mock. Prioridad **alta**. |
| C-012 | Alta | 2FA habilitado en config sin trait ni columnas; provider app no registrado | `fortify.php:152-156`; `User.php`; migraciones | Decidir: implementar 2FA completo o desactivar la feature en config hasta hacerlo. Prioridad **alta**. |

---

## Priorización consolidada

| Prioridad | IDs | Acción sugerida (tarea futura) |
|-----------|-----|--------------------------------|
| Alta | C-010 | Workflow GitHub Actions mínimo |
| Alta | C-005, C-012 | Auditar/unificar auth Fortify vs custom; alinear o apagar 2FA |
| Alta | C-011, C-009 (parcial) | Feature tests Socialite + login/registro |
| Alta (aplazado) | C-001 | Rotación credenciales — solo tras decisión login social |
| Media | C-004 | Consolidar Ciudad/Sector/TipoEmpresa con pruebas |
| Media | C-003, C-006 | Limpieza TokenSeeder post-3.0; lógica razas peligrosas |
| Baja | C-002, C-007, C-008 | Placeholders `.env.example`, tidy de nombres y controladores muertos |

## Límites de esta auditoría

- Solo lectura; no se ejecutó la suite completa ni se modificó código.
- No se leyó `.env`.
- No se inspeccionó el historial git commit-a-commit en busca de secretos
  (se reutiliza el estado documentado en `pendiente-rotacion-credenciales.md`).
- La conclusión sobre `FortifyServiceProvider` de la app se basa en
  `bootstrap/providers.php` y auto-discovery del paquete; no se arrancó
  la app para volcar el contenedor de servicios.
