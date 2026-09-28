---
agente: claude
estado: pendiente
rama:
archivos: [app/Console/Commands/, database/seeders/UserSeeder.php, database/seeders/TokenSeeder.php, database/seeders/DatabaseSeeder.php, tests/Feature/CrearSuperadminCommandTest.php, docs/]
---

# SEG-007: crear el primer Superadmin sin contraseñas fijas

UserSeeder y TokenSeeder crean usuarios con contraseñas fijas ('root', '12345678')
publicadas en el repositorio. En producción, el primer administrador debe
crearse con un comando.

## Qué hacer
1. Comando artisan `modustack:crear-superadmin`:
   - Pide nombre y correo (o `--name` y `--email`).
   - La contraseña NUNCA se pasa como argumento (quedaría en el historial de la
     shell): se pide de forma oculta, o con `--generar` se crea una aleatoria
     fuerte y se muestra UNA sola vez.
   - Asigna el rol Superadmin. Si el rol no existe, falla con un mensaje claro.
   - Si el correo ya existe, falla sin modificar nada.
2. UserSeeder y TokenSeeder: no hacen nada si el entorno no es `local` ni
   `testing`, y lo avisan por consola.
3. Pruebas primero (commit que FALLE) en `tests/Feature/CrearSuperadminCommandTest.php`:
   creación correcta con rol y contraseña hasheada, rechazo de correo
   duplicado, rechazo si falta el rol, y UserSeeder sin efecto en `production`.
4. Documenta el procedimiento de despliegue en el entregable.

## Entregable
`docs/auditorias/seg014-superadmin-inicial.md`.

## Restricciones
- No toques rutas, `.env`, dependencias ni controladores.
- Todas las pruebas (118 y las nuevas) deben pasar.
- Al terminar, vuelve con `git switch --detach origin/main`.
