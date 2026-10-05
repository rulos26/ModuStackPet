# SEG-045 — Frontend, Fase 1 parcial (FE-05, FE-07, FE-08, FE-13, FE-21)

Fecha: 2026-10-04 · Agente: Claude · Rama: `ia/claude/frontend-fase1`
Origen: `docs/auditorias/informe-unificado-frontend.md`. Solo vistas Blade; **no** se tocaron `layouts/app.blade.php`, `layouts/navbar.blade.php` ni ningún sidebar (los reescribe la Fase 2), ni `.env`, ni dependencias.

## Qué se corrigió

### FE-08 — Rutas de imagen que daban 404
Causa: `asset('public/storage/…')` y `asset('public/' . $ruta)` generan `/public/…`, que el servidor no sirve (`/storage/img/logo.jpg` sí responde 200; los archivos se comprueban con `public_path($ruta)`, o sea, la URL correcta es `asset($ruta)`).
Cambio (grep previo de todas las referencias; 25 líneas en 15 vistas):
- `asset('public/storage/…')` → `asset('storage/…')`
- `asset('public/' . $x)` → `asset($x)` (la ruta guardada ya empieza por `storage/…` o `avatars/…`, igual que el `file_exists(public_path($x))` previo)
- `empresa/form.blade.php`: `asset('public/' . $empresa->logo)` → `asset('storage/' . $empresa->logo)` (igual que el accesor `Empresa::logo_url`)
Vistas: `auth/login`, `cliente/dashboard`, `mascota/show`, `mensaje-de-bienvenida/{index,show}`, `empresa/form`, `user/{show,form}`, `user/{admin,cliente,paseador,superadmin}/form`, `user/{admin,cliente}/show`.

### FE-13 — `console.log` de depuración
- `auth/login.blade.php`: se eliminó todo el bloque de depuración (registraba correo, longitud de contraseña, método y CSRF; incluía un `alert()` de "método debe ser POST" que nunca debía dispararse). Se conservan `togglePassword()` y el ajuste de tema.
- `empresa/form.blade.php` (10 `console.log`) y `document-requirements/index.blade.php` (1).
- Se conservan los `console.error`/`console.warn` de bloques `catch`/error real (diagnóstico legítimo, no filtran datos del usuario). `layouts/app.blade.php` (warning de Vite) queda para la Fase 2.

### FE-21 — `\r\n` literales en el mensaje de bienvenida
Causa: el dato guardado contiene la secuencia de 4 caracteres `\r\n` (barra invertida + r + barra invertida + n), no saltos reales; las vistas la imprimían tal cual. Corrección en las vistas (el dato no se toca): `str_replace(['\r\n', '\n', '\r'], ' ', $descripcion)` antes del `explode('.')` en los dashboards de **Superadmin, Admin, Cliente y Paseador**, y en `mensaje-de-bienvenida/{index,show}`.

### FE-07 — `forgot-password`
Reescrita con el mismo diseño que el login (Bootstrap 5.3.0 en tarjeta, `<main>`, `lang="es"`), título "Recuperar contraseña", mensaje de estado (`role="status"`), errores (`role="alert"` + `is-invalid`), `old('email')`, `autocomplete="email"`, `spellcheck="false"` y botón "Enviar enlace de recuperación"; enlace "Volver a iniciar sesión". Los mensajes de Laravel salen en español (hay `resources/lang/es`; la prueba lo comprueba).

### FE-05 — Un solo `<h1>` y título correcto (solo vistas hijas)
El layout imprime `<h1>@yield('template_title')</h1>` y `<title>`. Cambios:
- `@section('title', …)` → `@section('template_title', …)` en `admin|cliente|paseador|superadmin/dashboard`, `dashboard`, `cliente/verificacion-datos` (antes `<h1>` vacío y `<title>` genérico).
- `<h1>` de contenido → `<h2 class="h1 …">` (misma apariencia) en los 4 dashboards y `dashboard.blade.php`; en `cliente/dashboard` el `<h4>` del nombre pasa a `<p class="h4">` (no salta niveles).
- `auth/verify-email` y las 4 vistas de `vacunas-certificaciones/` (copia con guion; las rutas usan `vacunas_certificaciones`) ahora definen `template_title`; en `verify-email` el `<h3>` pasa a `<h2 class="h3">`.
- De paso: el dashboard del Paseador decía "Dashboard Cliente" → "Dashboard Paseador".

## Pruebas
`tests/Feature/FrontendFase1Test.php` (11 casos): ninguna vista contiene `console.log`; el login no emite `console.`; ninguna vista (salvo `layouts/navbar` y `layouts/sidebar`, pendientes de Fase 2) usa `asset('public/…')`; el logo del login apunta a `/storage/img/logo.jpg` y el archivo existe; `forgot-password` (idioma, estilos, `<main>`, `autocomplete`, un `<h1>`, estado de éxito y error visibles y en español); los 4 dashboards (un único `<h1>` con contenido, `<title>` correcto, sin `\r\n` literales, frases separadas); `verify-email` con título.
Suite completa: **324 passed** (313 + 11). Además `php artisan view:cache` compila todas las vistas sin error.
Las pruebas se escribieron **después** de los cambios (no hay commit previo que falle); la causa de cada hallazgo sí se reprodujo con las mediciones del informe unificado.

## Lo que no se pudo / queda pendiente
- **`layouts/navbar.blade.php` (líneas 50-77) y `layouts/sidebar.blade.php:5` siguen con `asset('public/…')`**: el logo del sidebar y el avatar de la navbar —lo más visible del panel— seguirán dando 404 hasta la Fase 2 (restricción de la tarea). Una prueba lista exactamente esos dos archivos como excepción para que no se olvide.
- **`default.png` no existe**: las vistas piden `storage/img/default.png`, pero el archivo versionado se llama **`desfault.png`** (y `avatar/desfault.png`, `avatar/desfault copy.png`). Nada del código referencia "desfault". Arreglo mínimo: un `git mv public/storage/img/desfault.png public/storage/img/default.png` (más el de `avatar/`). No lo hice: renombrar binarios versionados queda fuera de "solo vistas"; decídelo tú.
- No se verificó en navegador (solo pruebas HTTP/Blade y compilación de vistas).
- `\r\n` en el **dato**: el texto guardado en BD sigue conteniendo la secuencia literal; si se prefiere limpiar el origen (formulario/seeder), es una tarea aparte.
- FE-02/03/04/09/10/11/12/14… del informe unificado (nombres accesibles, `<main>` del layout, sidebars, foco, tema…) no son parte de esta fase.
