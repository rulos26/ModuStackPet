---
agente: claude
estado: terminada
rama: ia/claude/comando-superadmin-inicial
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

## Handoff
- Agente y fecha: Claude, 2026-09-28.
- Qué se hizo: comando `modustack:crear-superadmin` (opciones `--name`,
  `--email`, `--generar`; contraseña nunca como argumento, se pide oculta
  con confirmación o se genera aleatoria y se muestra una sola vez); falla
  si el rol `Superadmin` no existe o si el correo ya está en uso, sin
  modificar nada. `UserSeeder` y `TokenSeeder` ahora no hacen nada fuera de
  `local`/`testing` (avisan por consola). Hallazgo adicional documentado:
  `TokenSeeder` referencia `App\Models\AdminDashboard\Token`, una clase que
  no existe (y no hay tabla `tokens`); ya estaba comentado en
  `DatabaseSeeder`, no se tocó por estar fuera de alcance. Detalle completo
  en `docs/auditorias/seg014-superadmin-inicial.md`.
- Archivos modificados: `app/Console/Commands/CrearSuperadminCommand.php`
  (nuevo), `database/seeders/UserSeeder.php`,
  `database/seeders/TokenSeeder.php`,
  `tests/Feature/CrearSuperadminCommandTest.php` (nuevo),
  `docs/auditorias/seg014-superadmin-inicial.md` (nuevo).
- Cómo probarlo: `php artisan test` (125 pruebas, 296 aserciones, todas en
  verde) y `composer validate`.
- Pendientes y riesgos: la política de contraseña manual solo exige mínimo
  8 caracteres (no complejidad); `--generar` sí produce una contraseña
  fuerte por construcción. `TokenSeeder` sigue siendo código muerto (ver
  arriba), no se eliminó por no ser parte del alcance pedido.
- Preguntas para el humano: ¿se debe eliminar `TokenSeeder` (y su llamada
  comentada en `DatabaseSeeder`) ya que referencia una clase inexistente, o
  se prefiere documentarlo para una tarea aparte?
