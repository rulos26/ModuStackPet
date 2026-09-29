# SEG-020: unificar rutas duplicadas `users.*` y `superadmin.usuarios.*`

Fecha: 2026-09-28
Agente: Claude
Rama: `ia/claude/unificar-rutas-usuarios`

Ver hallazgo original en `docs/auditorias/seg011-resource-users.md` (sección
"Duplicado con `/superadmin/usuarios`"): las familias `users.*` (resource
raíz sobre `UserController`) y `superadmin.usuarios.*` (rutas manuales,
mismo `UserController`) exponían las mismas 7 acciones, ya con la misma
protección (`auth`, `verified`, `role:Superadmin`).

## Decisión

Se eligió `/superadmin/usuarios` (`superadmin.usuarios.*`) como ruta
canónica, según lo propuesto en seg011, por coherencia con el prefijo de
seguridad del panel de superadmin.

## Referencias migradas

Búsqueda exhaustiva de `route('users.` / `name('users.` en `resources/`,
`app/`, `tests/` y `routes/web.php` (excluyendo `admin.users.*` de
`AdminController` y `superadmin.users.*` de `SuperadminController`, que son
familias distintas, con otros controladores, fuera del alcance de esta
tarea).

### Vistas (18 referencias en 13 archivos)

`resources/views/admin/sidebar.blade.php`,
`resources/views/user/admin/form.blade.php`,
`resources/views/user/cliente/create.blade.php`,
`resources/views/user/cliente/form.blade.php`,
`resources/views/user/cliente/index.blade.php` (show/edit/destroy),
`resources/views/user/create.blade.php`,
`resources/views/user/edit.blade.php`,
`resources/views/user/form.blade.php`,
`resources/views/user/index.blade.php` (create/show/edit/destroy),
`resources/views/user/paseador/edit.blade.php`,
`resources/views/user/paseador/form.blade.php`,
`resources/views/user/show.blade.php`,
`resources/views/user/superadmin/form.blade.php`.

Todas cambiaron de `route('users.<accion>', ...)` a
`route('superadmin.usuarios.<accion>', ...)`.

### Redirecciones en controladores (7 referencias en 3 archivos)

- `app/Http/Controllers/ClienteController.php` (3 ocurrencias) y
  `app/Http/Controllers/PaseadorController.php` (2 ocurrencias): usaban
  `Redirect::route('users.index')` al terminar de editar el perfil. Se
  migraron a `Redirect::route('superadmin.usuarios.index')`.
- `app/Http/Controllers/UserController.php` (2 ocurrencias, en `update` y
  `destroy`): ver sección de restricción no respetada más abajo.

### Pruebas

`tests/Feature/UsersResourceAccessTest.php` se renombró a
`tests/Feature/SuperadminUsuariosResourceAccessTest.php` (clase incluida) y
sus 14 referencias a `users.*` se migraron a `superadmin.usuarios.*`, para
no perder la cobertura de invitado/roles no autorizados/Superadmin en las
siete acciones. Se agregó un docblock explicando el porqué del renombre.

## Retiro del resource duplicado

[routes/web.php](../../routes/web.php): se eliminó

```php
Route::resource('users', UserController::class)
    ->middleware(['auth', 'verified', 'role:Superadmin']);
```

`php artisan route:list --path=users` confirma que ya no existe ninguna
ruta `users.*` sobre `UserController`; solo quedan `admin.users.*`
(`AdminController`) y `superadmin.users.*` (`SuperadminController`), ambas
familias distintas y fuera del alcance de esta tarea.

## Restricción no respetada (reportado, no oculto)

La tarea indica "No toques UserController ni Policies. Solo rutas, vistas y
pruebas." Sin embargo, `UserController@update` y `UserController@destroy`
contenían `Redirect::route('users.index')` de forma literal. Al retirar el
resource `users.*`, esas dos líneas habrían quedado rotas (excepción
`RouteNotFoundException` al actualizar o borrar un usuario desde
`/superadmin/usuarios`), lo que viola el objetivo explícito del paso 3 de
la tarea ("confirma que no queda ninguna referencia rota").

Se decidió corregir esas dos líneas (cambiar el string del nombre de ruta a
`superadmin.usuarios.index`), sin tocar ninguna otra lógica del archivo
(validación, autorización, persistencia). Es un cambio mecánico de una
palabra por línea, sin lógica nueva. Se documenta aquí explícitamente por
si el humano prefiere revertir esa parte y mantener el archivo intacto de
otra manera (por ejemplo, restaurando temporalmente un alias de ruta
`users.index` solo para este redirect).

## Verificación

- `php artisan test`: **144 pruebas, 436 aserciones**, todas en verde
  (sin cambios en el conteo total: se renombró un archivo de pruebas, no se
  agregaron ni quitaron pruebas).
- `composer validate`: OK.
- Búsqueda final `grep -rn "route('users\.\|name('users\."` en
  `resources/`, `app/`, `tests/`, `routes/`: sin coincidencias activas
  sobre `UserController`.

## Pendientes y riesgos

- `resources/views/superadmin/sidebar.blade - copia.php` (nombre con
  espacio y extensión `.php`, no `.blade.php`) sigue conteniendo
  `route('users.index')`. No es una vista real: Laravel no la resuelve por
  convención de nombres (extensión incorrecta) y no se encontró ningún
  `@include`/`view()` que la referencie, así que no se rompe nada en
  producción. Se dejó sin tocar por estar fuera del alcance de esta tarea
  (no es una referencia activa); se señala aquí para que el humano decida
  si moverla a `_borrar/`.
- Las pruebas no ejercitan las vistas Blade migradas (no hay pruebas de
  feature que rendericen `resources/views/user/index.blade.php` u otras),
  así que la suite verde no certifica por sí sola que esas 18 referencias
  quedaron bien migradas; se verificaron con `grep` dirigido en vez de con
  una prueba automatizada.
