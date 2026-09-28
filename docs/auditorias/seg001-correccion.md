# SEG-001: exigir el rol Superadmin en el grupo /superadmin

Fecha: 2026-09-28
Agente: claude (rama `ia/claude/seg001-rol-superadmin`)

## Hallazgo original

`routes/web.php` (grupo `superadmin`, antes de la corrección):

```php
Route::middleware(['auth','verified'])->prefix('superadmin')->name('superadmin.')->group(function () {
```

Solo exigía sesión iniciada y correo verificado. Cualquier usuario
autenticado con rol Admin, Cliente o Paseador podía entrar a todo el panel
Superadmin: gestión de usuarios, configuración de base de datos, backups,
migraciones, proveedores OAuth y configuraciones del sistema, incluidas
acciones destructivas como ejecutar backups o migraciones.

## Commit 1 — pruebas que demuestran el hueco

Archivo nuevo: `tests/Feature/SuperadminAccessTest.php`. Roles tomados de
`database/seeders/roleSeeder.php` (`Superadmin`, `Admin`, `Cliente`,
`Paseador`).

Con la suite ejecutada **antes** de la corrección:

```
Tests:  15 failed, 9 passed (25 assertions)
```

- **15 fallaron** (la evidencia del hueco): para los 3 roles no-Superadmin
  (Admin, Cliente, Paseador), las 8 rutas índice del grupo (`dashboard`,
  `usuarios.index`, `database-configs.index`, `email-configs.index`,
  `backup-configs.index`, `migrations.index`, `oauth-providers.index`,
  `configuraciones.index`) y las 4 acciones destructivas
  (`backup-configs.execute`, `migrations.execute`,
  `database-configs.store`, `usuarios.store`) no devolvían 403: unas
  devolvían 200 (acceso concedido) y otras 302 (redirección tras pasar la
  validación del formulario, es decir, el request llegó al controlador).
- **9 pasaron**: el invitado sin sesión ya era redirigido a `login`
  correctamente (esa parte no estaba rota), y Superadmin nunca recibía 403
  (comportamiento esperado que debía conservarse).

Commit: `test: demostrar que /superadmin no exige el rol Superadmin
(SEG-001)`.

## Commit 2 — la corrección

1. `bootstrap/app.php`: se registró el alias `role` (y de paso
   `permission` y `role_or_permission`, que tampoco estaban registrados)
   apuntando a los middleware de `spatie/laravel-permission`:

   ```php
   ->withMiddleware(function (Middleware $middleware) {
       $middleware->alias([
           'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
           'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
           'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
       ]);
   })
   ```

   Se confirmó que hacía falta: el proyecto migró a la estructura de
   Laravel 11/12 sin `app/Http/Kernel.php` activo (ese archivo sigue en el
   repo con los alias antiguos bajo el namespace `Spatie\Permission\Middlewares`
   — con "s" — pero `Illuminate\Contracts\Http\Kernel` resuelve en runtime a
   `Illuminate\Foundation\Http\Kernel`, no a la clase del proyecto, así que
   ese archivo nunca se ejecuta y sus alias nunca se registraban). No se
   tocó `app/Http/Kernel.php`: sigue siendo código muerto, fuera del
   alcance de esta tarea (no está en `archivos:` de la tarea 008).

2. `routes/web.php`: se añadió `role:Superadmin` al middleware del grupo:

   ```php
   Route::middleware(['auth','verified','role:Superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
   ```

   No se modificó ningún controlador ni ninguna otra ruta.

### Resultado después de la corrección

```
Tests:  56 passed (128 assertions)
```

Las 32 pruebas que ya existían siguen pasando, y las 24 de
`SuperadminAccessTest` (15 que antes fallaban + 9 que ya pasaban, más las
de Superadmin en las 8 rutas índice contadas como casos de
`DataProvider`) pasan todas.

## Nota sobre rutas duplicadas detectada durante la verificación

`routes/web.php` define **tres** rutas `GET /superadmin/dashboard` con el
mismo nombre `superadmin.dashboard`:
- Línea 121: sin ningún middleware de autenticación.
- Línea 148: dentro de un grupo `['auth','verified']` suelto (sin rol).
- Línea 313: dentro del grupo `superadmin` ahora corregido con `role:Superadmin`.

`Illuminate\Routing\RouteCollection` indexa las rutas por
`método+URI`, así que cada definición nueva **sobrescribe** a la anterior
con la misma URI: en tiempo de ejecución solo existe la de la línea 313
(la última registrada), que es la protegida. Las de las líneas 121 y 148
quedan como código muerto e inalcanzable, no como una ruta alternativa sin
protección. Se deja documentado en la lista de "Además" por higiene, pero
no representa un riesgo de seguridad activo y no se tocó (está fuera del
alcance: modificar esas líneas sería "modificar otras rutas").

## Además: otros grupos o rutas administrativas sin comprobación de rol

Solo se reporta, no se corrige (fuera de alcance de esta tarea).

### Sin ninguna comprobación de autenticación (`auth`)

Accesibles incluso sin iniciar sesión, solo protegidas (si acaso) por
`CheckModuleStatus`, que no comprueba identidad ni rol:
- `Route::resource('users', UserController::class)` (línea 172): CRUD
  completo de usuarios totalmente abierto, sin ningún middleware.
- `mensaje-de-bienvenidas` (línea 135-137).
- `tipo-documentos` (línea 173-175).
- `mascotas`, `razas`, `barrios` (líneas 181-189).
- `/pdf`, `/pdf/mascota` (reportes, líneas 190-193).
- `vacunas_certificaciones` (línea 194-196).
- `departamentos`, `ciudades` (+ `toggle-status`), `sectores` (líneas
  199-208).
- `tipos-empresas`, `empresas` (+ `pdf`) (líneas 211-215, y el duplicado
  de `empresas/{empresa}/pdf` en la línea 252, también sin middleware).
- `paths-documentos` (+ `create`, `store`, `toggle-status`) (líneas
  255-261).

### Con `auth` (y a veces `verified`) pero sin rol

Cualquier usuario autenticado, sea cual sea su rol, puede entrar:
- `/usuarios/roles` (GET) y `/usuarios/roles/{user}` (POST, asigna roles)
  (líneas 176-179): **cualquier usuario logueado puede asignarse a sí
  mismo o a otro usuario el rol Superadmin** — es una escalada de
  privilegios directa hacia el propio hueco que corrige SEG-001.
- Grupo `prefix('admin')` con solo `['auth']` (líneas 264-268):
  `admin.dashboard`, CRUD de usuarios (`AdminController`) y
  `toggle-status`. No exige rol Admin ni Superadmin.
- Grupo `prefix('cliente')` con solo `['auth']` (líneas 271-282): perfil de
  cliente y árbol genealógico accesibles por cualquier rol (Admin,
  Paseador, Superadmin también, no solo Cliente).
- Grupo `prefix('paseador')` con solo `['auth']` (líneas 285-291): perfil
  de paseador, mismo problema.
- Grupo `prefix('admin')` con `['auth','verified']` para
  `document-requirements` (líneas 294-299): solo filtra por módulo activo,
  no por rol.
- `mascota-documents` (+ `aprobar`, `rechazar`, `descargar`) con
  `['auth','verified']` (líneas 302-309): cualquier usuario autenticado
  puede aprobar o rechazar documentos de mascotas de cualquier otro
  usuario.

## Restricciones respetadas

- No se tocó `.env` ni dependencias.
- No se modificó ningún controlador.
- No se modificó ninguna otra ruta fuera del grupo `superadmin` (las
  rutas duplicadas de `superadmin.dashboard` en las líneas 121 y 148 se
  dejaron intactas, documentadas arriba).
- No se debilitó ni desactivó ninguna prueba existente; las 32 originales
  siguen intactas y pasando.
