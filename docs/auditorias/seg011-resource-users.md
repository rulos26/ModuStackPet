# SEG-011: protección de `Route::resource('users')`

Fecha: 2026-09-28  
Agente: Codex  
Rama: `ia/codex/proteger-resource-users`

## Hallazgo y prueba roja

El resource raíz `users.*` exponía las siete acciones de `UserController` sin
middleware propio. El grupo `web` se aplicaba por defecto, pero no exigía sesión,
correo verificado ni rol. En consecuencia, invitados y usuarios con los roles
`Admin`, `Cliente` y `Paseador` alcanzaban el controlador.

Los nombres de rol usados en la prueba se tomaron de
`database/seeders/roleSeeder.php`: `Superadmin`, `Admin`, `Cliente` y `Paseador`.

El commit rojo `9b5e34e8` añadió
`tests/Feature/UsersResourceAccessTest.php`. Antes de corregir las rutas el
resultado fue **28 fallos y 2 pruebas aprobadas**:

- 7 fallos de invitado: `index`, `show`, `create`, `edit`, `store`, `update` y
  `destroy` no redirigían al login.
- 21 fallos para `Admin`, `Cliente` y `Paseador`: cada rol podía alcanzar las
  siete acciones en lugar de recibir 403.
- Las 2 lecturas de Superadmin (`index` y `show`) ya no devolvían 403.
- En el caso `store`, la petición alcanzó la lógica de persistencia antes de
  fallar por el error preexistente `no such table: paseadors`. Esto confirma que
  no existía una barrera de autorización previa. No se corrigió ese defecto por
  estar fuera del alcance y porque, tras la protección, una petición no
  autorizada ya no alcanza el controlador.

Las pruebas verifican además que `store`, `update` y `destroy` no cambian la
tabla `users` cuando el solicitante es invitado o tiene un rol no autorizado.
Nunca ejecutan esas tres acciones como Superadmin.

## Corrección

El commit `b34bc331` aplicó al resource completo:

```php
Route::resource('users', UserController::class)
    ->middleware(['auth', 'verified', 'role:Superadmin']);
```

Después del cambio, las 30 pruebas de `UsersResourceAccessTest` pasan con 61
aserciones. `php artisan route:list --path=users -v` confirma los tres
middlewares en las siete rutas `users.*`.

## Duplicado con `/superadmin/usuarios`

Sí existe un duplicado funcional. Las rutas raíz `/users` (`users.*`) y las
rutas `/superadmin/usuarios` (`superadmin.usuarios.*`) exponen las mismas siete
acciones de `UserController`: `index`, `create`, `store`, `show`, `edit`,
`update` y `destroy`. Tras esta corrección ambas familias también tienen la
misma protección `auth`, `verified`, `role:Superadmin`.

No se eliminó ninguna ruta. El árbol actual contiene referencias activas a las
dos familias: por ejemplo, `resources/views/admin/sidebar.blade.php` y varias
vistas `resources/views/user/` usan `users.*`, mientras que
`resources/views/superadmin/sidebar.blade.php` y las vistas
`resources/views/user/superadmin/` usan `superadmin.usuarios.*`.

Se propone elegir `/superadmin/usuarios` como ruta canónica por coherencia con
el panel y su prefijo de seguridad, migrar primero todas las referencias a
`users.*`, añadir una ventana de compatibilidad si existen enlaces externos y
solo después retirar el resource raíz en una tarea separada.

## `AuthServiceProvider` no registrado

`bootstrap/providers.php` registra únicamente `AppServiceProvider` y
`ViewServiceProvider`; por tanto, `App\Providers\AuthServiceProvider` no se
instancia ni ejecuta su método `boot()`.

El contenido actual de ese provider es exhaustivamente:

- Una única entrada en `$policies`: `App\Models\Module` se asocia con
  `App\Policies\ModulePolicy`.
- Un método `boot()` vacío, salvo por un comentario.
- No define ningún `Gate::define`, `Gate::before`, `Gate::after` ni otros
  callbacks de autorización.

Lo que dejó de aplicarse por la falta de registro es la asociación **explícita**
de `Module` con `ModulePolicy`. En el estado actual, Laravel descubre esa policy
por convención de nombres, de modo que sus métodos `viewAny` y `update` (ambos
limitados a Superadmin) pueden seguir resolviéndose. Esa coincidencia evita un
impacto observable hoy, pero no vuelve operativo al provider: cualquier futura
policy no convencional o cualquier gate/callback agregado allí quedaría
silenciosamente inactivo.

Registrar el provider ahora probablemente sería neutro para la asociación
actual, porque apunta a la misma policy que Laravel descubre por convención. El
riesgo es activar de golpe lógica latente que se añada o ya exista en `boot()`
en otra rama, alterar la precedencia de asociaciones futuras, o generar cambios
de autorización no cubiertos por pruebas. Por eso no se registra en esta tarea;
debe hacerse en un cambio dedicado, tras inventariar el archivo en la rama de
integración y ejecutar pruebas específicas de `ModulePolicy` y la suite completa.

