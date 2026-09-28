# SEG-010: escalada de privilegios en /usuarios/roles y Kernel.php sin efecto

Fecha: 2026-09-28
Agente: claude (rama `ia/claude/escalada-privilegios-kernel`)

## Parte A (URGENTE): /usuarios/roles

### Hallazgo confirmado

`routes/web.php` protegía el grupo solo con `['auth']`. El controlador
`RoleAssignmentController@asignarRoles` solo bloqueaba la operación si el
usuario **objetivo** ya tenía el rol `Superadmin` o `Admin`
(`$rolesProtegidos`), pero no comprobaba el rol de quien hacía la
petición. Además, `$request->validate(['roles' => ...])` valida un campo
que no se usa: el código llama a `$user->syncRoles($request->rol)`, un
campo distinto y sin validar.

Pruebas del **Commit 1** (`tests/Feature/RoleAssignmentAccessTest.php`),
antes de la corrección: **9 de 10 fallaron**. Se confirmó empíricamente
que:
- Un usuario con rol **Admin** podía asignar el rol **Superadmin** a otro
  usuario (con rol Cliente), porque el objetivo no tenía aún un rol
  protegido.
- Un usuario con rol **Cliente** o **Paseador** podía **auto-promoverse a
  Superadmin** directamente, por la misma razón.
- Solo el intento de un usuario **Admin** de auto-promoverse fue bloqueado
  por la propia lógica del controlador (su rol actual, Admin, sí está en
  `$rolesProtegidos`), y solo el acceso de Superadmin a `usuarios.roles.index`
  ya se comportaba bien; los demás 9 casos fallaron.

### Commit 2 — corrección (defensa en profundidad)

1. `routes/web.php`: el grupo ahora exige `['auth', 'role:Superadmin']`.
2. `app/Policies/UserPolicy.php` (nueva): métodos `manageRoles()` y
   `assignRole()`, ambos exigiendo `hasRole('Superadmin')`. Laravel la
   descubre automáticamente por convención de nombres para el modelo
   `User` (igual que `ModulePolicy` para `Module`), sin necesitar tocar
   `AuthServiceProvider`.
3. `RoleAssignmentController`: `index()` llama a
   `$this->authorize('manageRoles', User::class)` y `asignarRoles()`
   llama a `$this->authorize('assignRole', $user)`. Así, si el middleware
   de la ruta volviera a perder el chequeo de rol (como pasó con
   `/superadmin` en SEG-001), el controlador seguiría rechazando la
   petición.

No se tocó la validación de `roles` vs `rol` ni la lista
`$rolesProtegidos`: son un bug funcional preexistente, no la causa de la
escalada de privilegios, y la tarea solo autoriza "cambios de
autorización" en controladores.

### Resultado

`tests/Feature/RoleAssignmentAccessTest.php`: 10/10 pasan. Suite completa:
66 pruebas (56 previas + 10 nuevas), todas en verde.

## Parte B: app/Http/Kernel.php sin efecto

### Cómo se confirmó que no se ejecuta

`Illuminate\Contracts\Http\Kernel` resuelve en tiempo de ejecución a
`Illuminate\Foundation\Http\Kernel` (verificado con
`php artisan tinker --execute="echo app(\Illuminate\Contracts\Http\Kernel::class)::class;"`),
no a `App\Http\Kernel`. Desde la migración a la estructura de Laravel 11
(sin `bootstrap/app.php` referenciando ese archivo), `app/Http/Kernel.php`
quedó huérfano: nada lo instancia ni lo usa.

### Tabla: Kernel.php vs bootstrap/app.php (antes de esta tarea)

| Elemento en `Kernel.php` | ¿Cubierto por defaults de Laravel 12? | ¿Registrado en `bootstrap/app.php`? | Acción |
|---|---|---|---|
| `$middleware` global: `TrustProxies`, `HandleCors`, `PreventRequestsDuringMaintenance`, `ValidatePostSize`, `TrimStrings`, `ConvertEmptyStringsToNull` | Sí, son los defaults automáticos de `Illuminate\Foundation\Configuration\Middleware::getGlobalMiddleware()` en Laravel 12 | No hace falta registrarlos | Ninguna |
| Grupo `web`: `EncryptCookies`, `AddQueuedCookiesToResponse`, `StartSession`, `ShareErrorsFromSession`, `VerifyCsrfToken`, `SubstituteBindings` | Sí (equivalentes exactos, con `ValidateCsrfToken` en vez de `VerifyCsrfToken`) | No hace falta registrarlos | Ninguna |
| Grupo `web`: `\App\Http\Middleware\SessionTimeout::class` | No (es un middleware propio del proyecto) | **No estaba registrado** | **Corregido**: `$middleware->web(append: [...])` en `bootstrap/app.php` |
| Grupo `api`: `AddQueuedCookiesToResponse`, `StartSession`, `SubstituteBindings` | Sí (equivalentes) | No hace falta | Ninguna |
| Alias `auth`, `auth.basic`, `cache.headers`, `can`, `guest`, `password.confirm`, `signed`, `throttle`, `verified` | Sí, son los `defaultAliases()` de Laravel 12 | No hace falta registrarlos | Ninguna |
| Alias `role`, `permission`, `role_or_permission` (spatie/laravel-permission) | No | No estaban registrados | **Ya corregido en la tarea 008** (SEG-001) |
| Alias `module.active` → `CheckModuleStatus` | No | No está registrado, **pero tampoco se usa**: todas las rutas invocan `\App\Http\Middleware\CheckModuleStatus::class . ':slug'` con la clase completa, nunca el alias `module.active` | Ninguna (alias muerto, sin impacto funcional ni de seguridad) |

Con esto, el único elemento de seguridad de `Kernel.php` que faltaba por
migrar era `SessionTimeout`, ya corregido en el Commit de la Parte B.

### Hallazgo adicional relacionado (solo reportar)

Durante la revisión se detectó que `app/Providers/AuthServiceProvider.php`
declara `protected $policies = [Module::class => ModulePolicy::class]`,
pero **ese provider no está registrado** en `bootstrap/providers.php`
(que solo lista `AppServiceProvider` y `ViewServiceProvider`). Es el mismo
patrón de "archivo huérfano" que `Kernel.php`. No es un hueco de seguridad
activo hoy: Laravel 12 descubre políticas automáticamente por convención
de nombres (`{Modelo}Policy` en `App\Policies`), y así es como
`ModulePolicy` y la nueva `UserPolicy` de esta tarea funcionan sin
necesitar el provider. Pero si en el futuro alguien necesita una política
con un nombre que no siga la convención, o registrar un `Gate::before`
global, ese código en `AuthServiceProvider::boot()` nunca se ejecutará y
el error será silencioso. `app/Providers/AuthServiceProvider.php` no está
en la lista `archivos:` de esta tarea, así que no se modifica; se deja
como pregunta para el humano en el Handoff.

### Decisión sobre `Kernel.php`

No se borra, según lo pedido. Se propone eliminarlo: no se ejecuta, y
mantenerlo es engañoso porque parece ser la fuente de verdad de la
configuración de middleware (alguien podría editarlo pensando que tiene
efecto, como probablemente pasó con `SessionTimeout`).

## Parte C: propuestas de rol para los grupos sin comprobación de rol

Lista original en `docs/auditorias/seg001-correccion.md`, sección
"Además". Solo se reporta, no se corrige.

### Sin ninguna comprobación de autenticación

| Ruta / grupo | Rol propuesto |
|---|---|
| `Route::resource('users', UserController::class)` (línea 172) | `Superadmin` (es CRUD de usuarios; ya existe un panel equivalente protegido en `/superadmin/usuarios`, esta ruta duplicada debería exigir lo mismo o eliminarse) |
| `mensaje-de-bienvenidas` | `Superadmin` (configuración del sistema) |
| `tipo-documentos` | `Superadmin` o `Admin` (catálogo administrativo) |
| `mascotas`, `razas`, `barrios` | Mínimo `auth` para todos los roles autenticados (datos operativos que Cliente/Paseador también consultan); las escrituras (`store`/`update`/`destroy`) deberían exigir `Admin` o `Superadmin` |
| `/pdf`, `/pdf/mascota` | `auth` (cualquier rol autenticado dueño de la mascota) más autorización a nivel de objeto (policy) para no exponer PDFs de mascotas ajenas |
| `vacunas_certificaciones` | Igual que `mascotas`: lectura para roles autenticados, escritura para `Admin`/`Superadmin` |
| `departamentos`, `ciudades`, `sectores` | `Superadmin` o `Admin` (catálogos administrativos) |
| `tipos-empresas`, `empresas` (+ `pdf`) | `Superadmin` o `Admin` |
| `paths-documentos` | `Superadmin` (configuración de rutas de almacenamiento) |

### Con `auth` pero sin rol

| Ruta / grupo | Rol propuesto |
|---|---|
| `/usuarios/roles*` | `Superadmin` — **ya corregido en esta tarea (Parte A)** |
| `prefix('admin')` con solo `auth` (líneas 264-268: `admin.dashboard`, CRUD de usuarios, `toggle-status`) | `Admin` y `Superadmin` |
| `prefix('cliente')` (líneas 271-282: perfil, árbol genealógico) | `Cliente` (y probablemente `Admin`/`Superadmin` para soporte), no todos los roles |
| `prefix('paseador')` (líneas 285-291: perfil) | `Paseador` (y probablemente `Admin`/`Superadmin` para soporte) |
| `prefix('admin')` con `auth,verified` para `document-requirements` (líneas 294-299) | `Admin` y `Superadmin` |
| `mascota-documents` (+ `aprobar`, `rechazar`, `descargar`) (líneas 302-309) | Subida: `Cliente` dueño de la mascota (via policy); aprobar/rechazar: `Admin` o `Superadmin` únicamente |

## Restricciones respetadas

- No se tocó `.env` ni dependencias.
- En controladores, solo se añadieron llamadas a `$this->authorize(...)`
  (autorización), sin tocar lógica de negocio.
- No se desactivó ni debilitó ninguna prueba existente. Las 56 pruebas
  previas siguen intactas; la suite completa ahora tiene 68 y todas pasan.
- No se borró `app/Http/Kernel.php`; se propone su eliminación arriba.
