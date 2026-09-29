# SEG-018: investigación del propósito de `TokenSeeder`

Fecha: 2026-09-28  
Agente: Codex  
Rama: `ia/codex/investigar-tokenseeder`

## Conclusión ejecutiva

`TokenSeeder` no corresponde a Sanctum ni a tokens de API. La evidencia apunta
a un prototipo anterior de **PIN temporal para habilitar opciones avanzadas de
Administrador/SuperAdministrador**. El registro guardaba el usuario, el rol, un
campo `password`, una fecha de vencimiento y una duración en días; una vista
histórica pedía ese PIN al intentar usar opciones avanzadas.

La funcionalidad quedó incompleta en todo el historial Git disponible: nunca se
encontró el modelo `App\Models\AdminDashboard\Token`, una migración para la tabla
`tokens`, una ruta funcional para el formulario histórico ni un controlador que
validara el PIN. En el estado actual, el seeder es código muerto y además falla
en `local`/`testing` si se invoca directamente.

**Recomendación: eliminar `TokenSeeder` y el SQL de datos huérfano en una tarea
separada**, junto con la llamada comentada. Antes de retirar también los permisos
`tokens.*` y el registro de módulo "Token de Seguridad", el humano debe confirmar
que no se pretende retomar esa función. Si se desea retomarla, debe diseñarse
como una funcionalidad nueva (modelo, migración, autorización, expiración,
almacenamiento seguro y pruebas), no completando el prototipo actual.

## Alcance y método

Se revisaron el historial completo disponible (`--all`), el árbol actual, rutas,
controladores, vistas, configuración, seeders, SQL auxiliar y documentación. No
se leyó ningún archivo `.env` ni se modificó código.

Comandos principales:

```text
git log --all --follow -- database/seeders/TokenSeeder.php
git log --all -S "AdminDashboard" --oneline
git log --all -- app/Models/AdminDashboard
git grep / rg sobre AdminDashboard, token, vencimiento y permisos tokens.*
```

## Línea de tiempo

### 2025-04-03 — primera aparición disponible

- Commit `935fc48a` (`juan.diaz`), mensaje genérico: "Agregar configuración de
  PostCSS, políticas de privacidad y términos de servicio, y componentes de
  interfaz de usuario".
- Añadió `TokenSeeder.php` y `database/sql/token.sql`. El seeder estaba
  completamente comentado y la llamada desde `DatabaseSeeder` también.
- Los dos registros eran para los usuarios 1 y 2, con roles
  `SuperAdministrador` y `Administrador`, y campos `password`, `vencimiento` y
  `dias`.
- El SQL conserva fechas de diciembre de 2023/enero de 2024. Esto demuestra que
  los datos procedían de una implementación anterior al historial Git disponible,
  pero el repositorio no contiene su esquema.
- El mensaje del commit no explica el propósito del seeder.

### 2025-04-03 — restauraciones de la base Laravel

- `39dab0b7` eliminó el seeder durante "volviendo a laravel base 12".
- `2d919521` lo restauró en el commit "prueba" y activó temporalmente su llamada
  después de `roleSeeder` y `UserSeeder`, antes de `ExecuteSqlSeeder`.
- Ese mismo estado histórico incluyó
  `resources/views/tipodocumento/modal/token.blade.php`: al intentar acceder a
  opciones avanzadas, el modal pedía un "PIN de seguridad" y enviaba el formulario
  a la ruta nombrada `tipodocumento.modal`. La vista índice mostraba acciones para
  "Activar SuperAdministrador" o "Activar Administrador".
- No se encontró la definición de esa ruta ni lógica de controlador que validara
  el PIN en el commit. Las inclusiones usaban rutas de vista bajo
  `AdminDashboard`, pero esa estructura tampoco existía en el árbol.

### 2025-04-05 — eliminación y restauración

- `5a71a9fd` eliminó de nuevo el proyecto base, incluido el seeder.
- `95b8fb7b` lo restauró con "se sube base laravel". En esta restauración se
  descomentaron las dos llamadas `Token::Create`, pero la llamada desde
  `DatabaseSeeder` quedó comentada. Ese es esencialmente el estado heredado hoy.

### 2026-09-28 — guard de entorno

- `d5dec49b` añadió una salida temprana fuera de `local`/`testing` como parte de
  SEG-007. El cambio redujo el riesgo en producción, pero no creó el modelo ni la
  tabla y documentó el seeder como código muerto.

## Evidencia del propósito probable

La hipótesis más probable es un **desbloqueo temporal de privilegios mediante
PIN**, no autenticación por token:

1. Los campos del seeder y de `token.sql` enlazan explícitamente usuario y rol,
   y añaden contraseña, vencimiento y días.
2. El modal histórico pide un "PIN de seguridad" para acceder a "opciones
   avanzadas".
3. La vista histórica etiqueta la acción como activación de Administrador o
   SuperAdministrador.
4. `roleSeeder.php` todavía crea permisos CRUD `tokens.index`, `tokens.create`,
   `tokens.show`, `tokens.edit` y `tokens.destroy`.
5. `database/sql/modulos.sql` todavía registra un módulo llamado "Token de
   Seguridad".

Los puntos 4 y 5 son restos coherentes con un CRUD administrativo planeado, pero
no prueban que llegara a implementarse. No hay rutas `tokens.*`, controlador,
modelo, migración ni vistas CRUD actuales.

## Estado actual

- `TokenSeeder` importa una clase inexistente:
  `App\Models\AdminDashboard\Token`.
- `git log --all -- app/Models/AdminDashboard` no devuelve ningún commit: esa
  carpeta/modelo no existió en el historial accesible.
- No hay migración propia para `tokens`. Las tablas de restablecimiento de
  contraseña y `personal_access_tokens` son funciones distintas.
- `DatabaseSeeder` conserva `// $this->call(TokenSeeder::class);` entre
  `UserSeeder` y `ExecuteSqlSeeder`, por lo que el seeder no se ejecuta en el
  flujo normal.
- `ExecuteSqlSeeder` conserva comentada la ejecución de `database/sql/token.sql`.
- Las referencias actuales no relacionadas con Laravel/Sanctum son los permisos
  `tokens.*`, el módulo "Token de Seguridad", el seeder, el SQL huérfano y la
  documentación de auditoría. No existe un consumidor funcional.
- La documentación general sí menciona dashboards administrativos y otros tipos
  de token (CSRF, recuperación, OAuth), pero no documenta este flujo de PIN
  temporal.

## Evaluación de alternativas

### Eliminar — recomendada

Retira código que no puede funcionar y reduce confusión entre este PIN, Sanctum,
CSRF y recuperación de contraseña. La eliminación debería abarcar como mínimo
`TokenSeeder`, `token.sql` y sus llamadas comentadas. Los permisos y el módulo
requieren confirmación funcional previa por si representan una intención de
producto aún vigente.

### Completar la funcionalidad — no recomendada sin requisitos nuevos

El prototipo no define amenazas, alcance del privilegio, forma segura de guardar
el PIN, rotación, expiración efectiva, auditoría ni recuperación. Además, elevar
roles mediante un PIN compartido aumentaría considerablemente el riesgo. Si el
negocio necesita aprobación temporal, conviene diseñar un flujo explícito de
elevación/reautenticación con requisitos y pruebas propios.

### Dejarlo documentado como pendiente — solo temporalmente

Es aceptable mientras el humano decide sobre la idea de producto, pero mantener
el seeder no aporta valor ejecutable. Este informe ya preserva la evidencia
histórica necesaria para poder eliminarlo sin perder contexto.

## Riesgos y límites de la investigación

- El historial Git comienza en 2025, mientras que los datos SQL están fechados
  en 2023. No puede determinarse si el modelo y la tabla existieron en un sistema
  anterior no versionado.
- Los mensajes de los commits iniciales son genéricos y no documentan decisiones
  funcionales; la conclusión se infiere de la combinación de campos, vistas,
  permisos y SQL.
- No se ejecutó ni reparó el seeder porque la tarea era exclusivamente de
  investigación y documentación.
