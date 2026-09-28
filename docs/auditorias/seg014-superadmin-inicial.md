# SEG-007 / SEG-014: crear el primer Superadmin sin contraseñas fijas

Fecha: 2026-09-28
Agente: Claude
Rama: `ia/claude/comando-superadmin-inicial`

## Hallazgo y prueba roja

`UserSeeder` y `TokenSeeder` creaban usuarios (`root@modustackpet.com` /
`root`, `rulos26@gmail.com` / `12345678`, etc.) con contraseñas fijas
publicadas en el repositorio, sin distinguir el entorno de ejecución. Si
`db:seed` se ejecutara alguna vez en producción, esas credenciales quedarían
activas y son públicas (están en el historial de git).

El commit `01c2526c` añadió `tests/Feature/CrearSuperadminCommandTest.php`
con 7 pruebas. Antes de la corrección, las 7 fallaban:

- Las 5 pruebas del comando `modustack:crear-superadmin` fallaban con
  `CommandNotFoundException` (el comando no existía).
- `test_user_seeder_no_crea_usuarios_en_production` fallaba con
  `RoleDoesNotExist` (UserSeeder no comprobaba el entorno y seguía
  intentando asignar el rol `Superadmin`, que no existía en ese test).
- `test_token_seeder_no_hace_nada_en_production` fallaba con
  `Class "App\Models\AdminDashboard\Token" not found` — un hallazgo
  adicional: `TokenSeeder` referencia una clase que no existe en el
  proyecto y no hay migración para una tabla `tokens`. Ya era código muerto
  (está comentado en `DatabaseSeeder`), pero antes de este cambio, si algo
  llegara a invocarlo en producción, rompería con un error de clase en vez
  de no hacer nada.

## Corrección

1. Comando nuevo
   [app/Console/Commands/CrearSuperadminCommand.php](../../app/Console/Commands/CrearSuperadminCommand.php)
   (`modustack:crear-superadmin`):
   - Acepta `--name` y `--email`, o los pide de forma interactiva si faltan.
   - Verifica primero que el rol `Superadmin` exista (vía Spatie
     `Role::where('name', 'Superadmin')->exists()`); si no, falla con
     mensaje claro y no crea nada.
   - Valida que el correo no esté en uso; si ya existe, falla sin modificar
     la base de datos.
   - La contraseña **nunca se acepta como opción/argumento** de línea de
     comandos (no hay `--password`), para que no quede en el historial de
     la shell:
     - Por defecto se pide oculta dos veces con `$this->secret(...)`
       (entrada + confirmación); si no coinciden o no cumplen el mínimo de
       8 caracteres, falla sin crear el usuario.
     - Con `--generar` se genera una contraseña aleatoria fuerte (mezcla de
       mayúsculas, minúsculas, números y símbolos) y se muestra **una sola
       vez** en la salida del comando, con advertencia de guardarla.
   - Crea el usuario (`password` se hashea automáticamente por el cast
     `'password' => 'hashed'` de `App\Models\User`) y le asigna el rol
     `Superadmin`.
2. [UserSeeder.php](../../database/seeders/UserSeeder.php) y
   [TokenSeeder.php](../../database/seeders/TokenSeeder.php): ahora
   verifican `app()->environment(['local', 'testing'])` al inicio de
   `run()`. Fuera de esos dos entornos, no crean nada y avisan por consola
   (`$this->command?->warn(...)`, con `?->` porque el seeder puede
   invocarse sin comando de consola detrás, como en las pruebas).

Tras la corrección, las 7 pruebas de `CrearSuperadminCommandTest` pasan, y la
suite completa (`php artisan test`) da **125 pruebas, 296 aserciones**, todas
en verde (118 previas + 7 nuevas). `composer validate` también pasa.

## Procedimiento de despliegue recomendado

En un entorno nuevo (`APP_ENV=production` o cualquier otro distinto de
`local`/`testing`):

1. Ejecutar las migraciones y el `roleSeeder` (o el flujo de seeders que ya
   cree los roles, sin `UserSeeder` ni `TokenSeeder`, que se autolimitan a
   `local`/`testing`).
2. Crear el primer Superadmin con:
   ```bash
   php artisan modustack:crear-superadmin --name="Nombre" --email="correo@dominio.com"
   ```
   El comando pedirá la contraseña de forma oculta (dos veces, para
   confirmarla). Para generarla automáticamente en vez de escribirla:
   ```bash
   php artisan modustack:crear-superadmin --name="Nombre" --email="correo@dominio.com" --generar
   ```
   La contraseña generada se muestra **una sola vez** en la terminal; debe
   copiarse de inmediato a un gestor de contraseñas, porque el comando no la
   guarda en ningún lado ni la vuelve a mostrar.
3. Si se necesitan más Admins/Clientes/Paseadores, crearlos desde el panel
   ya autenticado como ese Superadmin, no desde seeders.
4. Verificar que `db:seed` nunca se ejecute completo en producción sin
   revisar antes qué seeders corren; aunque `UserSeeder`/`TokenSeeder` ya no
   crean nada fuera de `local`/`testing`, otros seeders de la lista
   (`ModuleSeeder`, `DatabaseConfigSeeder`, etc.) sí insertan datos según
   `.env` y deben revisarse caso por caso antes de un `db:seed` en
   producción.

## Restricciones respetadas

No se tocaron rutas, `.env`, dependencias ni controladores. Solo se agregó
un comando de consola, se ajustaron dos seeders y se añadieron pruebas.

## Pendientes y riesgos

- `TokenSeeder` sigue siendo código muerto (referencia
  `App\Models\AdminDashboard\Token`, una clase inexistente, y no hay tabla
  `tokens`). Está comentado en `DatabaseSeeder` y ahora además protegido
  por el guard de entorno, pero si alguna vez se ejecuta en `local`/
  `testing` sin que exista esa clase, seguirá fallando con un error de
  clase no encontrada. No se corrigió por estar fuera del alcance de esta
  tarea (no se pidió arreglar `TokenSeeder`, solo que no actúe en
  producción); se documenta aquí para que se decida si conviene eliminarlo.
- El comando no impone una política de complejidad más allá del mínimo de 8
  caracteres para la contraseña ingresada manualmente (no exige mayúsculas/
  números/símbolos); la opción `--generar` sí produce una contraseña fuerte
  por construcción. Si se requiere una política más estricta, es un cambio
  aparte.
