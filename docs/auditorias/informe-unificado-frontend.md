# Informe unificado de frontend — hallazgos, prioridad y plan

Fecha: 2026-10-04 · Agente: Claude · Rama: `ia/claude/informe-unificado-frontend` · Tarea 044
Mismo espíritu que `informe-unificado-y-plan.md` (seguridad). **Solo lectura**: no se modificó nada de `app/` ni `resources/`; no se implementa nada, solo se propone un plan.

## 0. Fuentes y método

Se leyeron completas las 6 auditorías (5 independientes entre sí; las dos de Claude se hicieron antes de ver las demás). Abreviaturas usadas en todo el informe:

| Sigla | Archivo | Autor / herramienta | Tipo |
|---|---|---|---|
| **C1** | `auditoria-web-design-guidelines.md` | Claude · skill Vercel `web-design-guidelines` | estática, `archivo:línea` |
| **C2** | `auditoria-frontend-diseno.md` | Claude · `web-quality-audit` (Osmani) | **medición en vivo** (navegador, Performance API, `curl`) + estática |
| **X1** | `auditoria-web-design-guidelines-codex.md` | Codex · skill Vercel | estática, 149 vistas |
| **X2** | `auditoria-diseno-frontend.md` | Codex · segunda pasada (estilo Vercel) | estática, narrativa y plan |
| **U1** | `auditoria-web-design-guidelines-cursor.md` | Cursor · skill Vercel | estática, la más extensa (~135 filas) |
| **U2** | `frontend-web-quality-audit.md` | Cursor · `web-quality-audit` | **Lighthouse 12.2.1 real** (móvil) + estática |

**Confianza.** Hallazgo con ≥ 3 fuentes independientes = *muy alta*; 2 fuentes = *alta*; 1 fuente = *media* salvo que esté **medido** o que yo lo haya **verificado en código para este informe** (se indica "✔ verificado"). Los números de línea son los de cada auditoría (base `origin/main` `60e8debd`, todas coinciden entre sí en las líneas que se repiten). Severidad = impacto en el usuario/negocio, unificada por mí cuando las fuentes discrepan (ver §6).

**Tipos:** `ACC` accesibilidad · `RND` rendimiento · `VIS` consistencia visual · `DEP` deuda técnica de dependencias · `SEG` seguridad/robustez del frontend · `CNT` contenido/i18n.

## 1. Resumen ejecutivo

El frontend funciona, pero su causa raíz es estructural: **el panel se construye con librerías servidas por CDN que ningún gestor de paquetes controla y que no están pensadas para convivir** (AdminLTE 3 + Bootstrap 5 + jQuery + Font Awesome + SweetAlert2), mientras el pipeline propio (Vite/Tailwind) está medio conectado y casi sin uso. De ahí salen el peso de página (dashboard con **LCP 5.1 s**, 504 KiB, ~145 KiB de CSS sin usar), el tema oscuro roto, estilos inconsistentes entre pantallas y la imposibilidad de aplicar CSP/SRI. Encima de esa base, las 6 auditorías coinciden en el mismo bloque de accesibilidad básica (nombres accesibles, `<main>`/skip link, foco visible, `<h1>`), en que las pantallas de autenticación están fuera del layout y desalineadas, y en residuos de depuración.

Cifras clave (medidas por U2 y C2): login Lighthouse Perf **96** / A11y **92**; **dashboard Perf 69 / A11y 80, LCP 5.1 s**; listado `/razas` 28 peticiones y 685 KB; CLS aceptable (0–0.03); sin cabeceras de seguridad HTTP.

### Tabla resumen

| ID | Hallazgo | Severidad | Tipo | Nº fuentes | Confianza |
|---|---|---|---|---:|---|
| **FE-01** | **Stack UI por CDN sin gestionar, de generaciones incompatibles, + Tailwind desconectado** (sección propia §2) | **Alta (causa raíz)** | DEP · RND · VIS | 5 | muy alta ✔ |
| FE-02 | Controles solo-ícono sin nombre accesible (navbar, 👁️, ~30 "mostrar contraseña", acciones CRUD) | Alta | ACC | 6 | muy alta |
| FE-03 | Sin `<main>` ni skip link en el layout | Alta | ACC | 6 | muy alta |
| FE-04 | Navegación rota: sidebar de Admin comentado, **Paseador sin sidebar** (✔ ampliado), `href="#"` | Alta | VIS · ACC | 6 | muy alta ✔ |
| FE-05 | Títulos: `@section('title')` ≠ `template_title` → `<h1>` vacío y `<title>` genérico; doble `<h1>`; jerarquía | Alta | ACC · VIS | 4 | muy alta ✔ |
| FE-06 | Pantallas de auth fuera del layout y desalineadas (Bootstrap 5.3.0 vs 5.3.2, Arial vs Source Sans) | Alta | VIS · DEP | 5 | muy alta |
| FE-07 | `forgot-password` sin estilos, `lang="en"`, sin feedback de errores/estado | Alta | ACC · VIS | 3 | muy alta ✔ |
| FE-08 | Imágenes de marca/avatar con ruta `public/storage/…` → 404 | Alta | VIS · SEG(consola) | 3 | muy alta (medido) |
| FE-09 | Foco: `outline:none` sin reemplazo en 3 pantallas de auth; sin `:focus-visible` global | Alta | ACC | 3 | muy alta |
| FE-10 | Tema oscuro con 3 mecanismos incompatibles, color transparente, CSS no importado, sin `color-scheme` | Alta | VIS · ACC | 6 | muy alta |
| FE-11 | Contraste insuficiente (enlaces del login en oscuro 3.43:1; navbar/footer/“Acciones Rápidas”) | Alta | ACC | 2 | alta (medido ×2) |
| FE-12 | Controles de formulario sin `<label>` asociado (barrio, ciudad, empresa, módulos…) | Alta | ACC | 3 | alta |
| FE-13 | `console.log` de depuración con correo y longitud de contraseña (login) | Media | SEG | 6 | muy alta |
| FE-14 | Formularios sin `autocomplete`/`spellcheck`; credenciales SMTP/BD sin `autocomplete="off"` | Media | ACC · SEG | 4 | muy alta |
| FE-15 | Avisos asíncronos y toasts sin `aria-live`; toasts de 3 s también para errores | Media | ACC | 3 | alta |
| FE-16 | Dependencias de tablas (DataTables+Buttons+pdfmake+jszip) repetidas en 15 vistas | Media | RND · DEP | 3 | alta |
| FE-17 | 239 `style=""`, 39 `onclick`, bloques `<style>` por vista | Media | VIS · DEP | 4 | muy alta |
| FE-18 | `transition: all` (6) y 0 `prefers-reduced-motion` | Media | ACC · RND | 3 | alta |
| FE-19 | Imágenes sin `width/height`/`alt`/`lazy` | Media | RND · ACC | 5 | muy alta |
| FE-20 | Registro con `height:100vh` (y login `vh-100`) se desborda en pantallas bajas | Media | VIS | 3 | alta |
| FE-21 | Mensaje de bienvenida muestra `\r\n\r\n` literal | Media | CNT | 2 | alta (medido ×2) |
| FE-22 | Sin cabeceras de seguridad HTTP / SRI / CSP (+ `X-Powered-By`) | Media | SEG | 3 | alta |
| FE-23 | Marca y copy inconsistentes ("ModuStackPetLTE", "Dashboard Cliente" en Paseador), idioma mezclado, fechas con formato fijo | Baja | CNT | 4 | alta |
| FE-24 | SEO/meta mínimas (sin description; robots permisivo) | Baja | CNT | 2 | alta |
| FE-25 | Otros menores: `target=_blank` sin `rel`, `...` vs `…`, `touch-action`, `<select>` en oscuro, `alert()`/`confirm()` nativos | Baja | ACC · VIS | 4 | alta |
| FE-26 | Hallazgos únicos de una sola fuente (ver §4) | Var. | Var. | 1 | media |

## 2. FE-01 — Hallazgo prioritario: stack de UI por CDN, incompatible y sin gestionar

**Severidad:** Alta (causa raíz) · **Tipo:** DEP + RND + VIS · **Fuentes:** C2 (M-01, M-02, M-04, H-06), X2 (#3, #10), U2 (High perf, Medium SRI, Design system), X1 (`app.blade.php:17,18`), U1 (`app.blade.php:17-22`), C1 (`app.blade.php:81-84`) · **Estado:** ✔ verificado en código para este informe.

### 2.1 Qué está pasando (verificado)
`resources/views/layouts/app.blade.php` carga **por CDN** y sin `integrity`/`crossorigin`:

| Recurso | Versión | Línea | ¿En `package.json`/`composer.json`? |
|---|---|---|---|
| Bootstrap CSS / JS bundle | 5.3.2 | `app.blade.php:17`, `:82` | **No** |
| Font Awesome CSS | 6.4.2 | `:18` | **No** |
| SweetAlert2 CSS / JS | 11.7.32 | `:19`, `:83` | **No** |
| AdminLTE CSS / JS | `3.2` (rango flotante → recibe cualquier 3.2.x) | `:22`, `:84` | **No** |
| jQuery | 3.7.1 | `:81` | **No** |
| Source Sans Pro (Google Fonts) | — | `:14` | **No** |

Ninguna de estas dependencias figura en `package.json` ni `composer.json`: **no hay forma de auditarlas (`npm audit`, Dependabot), fijar versiones ni actualizarlas con control** (AdminLTE `3.2` se resuelve a "lo que sirva el CDN"). Además:

1. **Generaciones incompatibles:** AdminLTE 3.2 está construido sobre **Bootstrap 4 + jQuery**; el layout lo mezcla con **Bootstrap 5.3.2** y la navbar usa a la vez `data-widget="pushmenu"` (AdminLTE 3) y `data-bs-toggle="dropdown"` (BS 5) (`navbar.blade.php:5,15`). Es combinación no soportada por ninguno de los dos proyectos (X2 #3, C2 H-06, U2 "Design system").
2. **Pipeline propio mal conectado — matiz importante a la premisa de la tarea.** La tarea afirma que estas librerías conviven "simultáneamente con Tailwind 3.4.13 compilado localmente". **Verificado: Tailwind está declarado y configurado, pero NO se carga en el panel autenticado:**
   - `package.json` declara `tailwindcss ^3.4.13` (el lock resuelve **3.4.17**), con `tailwind.config.js` y `postcss.config.js`; `vite.config.js` incluye `resources/css/app.css` como entrada (con `@tailwind base/components/utilities`).
   - Pero `layouts/app.blade.php:134` solo pide `@vite(['resources/js/app.js'])` — **no el CSS** — y únicamente si existe `public/build/manifest.json` (`/public/build` está en `.gitignore` y no existe en el repo; sin él cae a `public/js/app.js`, copia manual desde `public/`). `resources/js/app.js` no importa el CSS (X2 #1).
   - El único sitio que sí pide `resources/css/app.css` vía Vite es `welcome.blade.php:15` (landing), que además **incrusta** un bloque de CSS Tailwind compilado a mano (v3.4.1) en el propio HTML.
   - Resultado: en el panel hoy **no hay conflicto Tailwind↔Bootstrap** (no se sirve Tailwind), pero sí hay: (a) un build de Vite que el panel casi no usa, (b) la landing con un sistema de estilos totalmente distinto, (c) riesgo latente: el día que se enganche `app.css` al layout, el *preflight* de Tailwind chocará con Bootstrap/AdminLTE. El estado actual es "dos sistemas de estilos, uno de ellos huérfano", no "tres cargados a la vez".
3. **Dos copias de la lógica de assets:** `resources/js/app.js` y `public/js/app.js` (+ `public/css/app.css` con tema `[data-theme]`) (U1 `public/js/app.js:1`, X1 `public/js/app.js:7`, X2 #1).

### 2.2 Consecuencias medidas (causa raíz de otros hallazgos)
| Consecuencia | Evidencia | Fuente |
|---|---|---|
| **LCP 5.1 s / FCP 5.0 s, Perf 69** en el dashboard (umbral "bueno" 2.5 s) | Lighthouse móvil, 504 KiB vs 72 KiB del login | U2 |
| **~145 KiB de CSS sin usar** (AdminLTE 110 + FA 18 + Bootstrap 16) | Lighthouse "unused CSS" | U2 |
| `/razas`: **28 peticiones, 685 KB, 6 orígenes externos**; DataTables+pdfmake re-cargados en 15 vistas | `Performance API` | C2, X2, U2 |
| Sin SRI en ~246 referencias; imposible activar CSP estricta | grep | C2, U2 |
| Tema oscuro roto y estilos inconsistentes entre pantallas | ver FE-10, FE-06 | X2, C2 |
| Warning permanente "Vite manifest no encontrado" y fallback manual | consola | C2 |

### 2.3 Qué se recomienda (propuesta, ver plan §7)
**Decisión humana primero**: elegir *una* base de UI (A: Bootstrap 5 puro + AdminLTE 4 [BS5]; B: AdminLTE 3 + Bootstrap 4; C: Tailwind + componentes propios) y **declarar todas las dependencias en `package.json`**, compilarlas con Vite (CSS+JS) y retirar los CDN; fijar versiones y añadir `npm audit` al flujo. Hasta decidir, no añadir más librerías de UI.

## 3. Hallazgos coincidentes entre varias auditorías (mayor confianza primero)

> Formato: **ID — título** · severidad · tipo · fuentes. Evidencia `archivo:línea` tal como la citan las auditorías (se indica de cuál).

### FE-02 · Controles solo-ícono sin nombre accesible — Alta · ACC · C1, C2, X1, X2, U1, U2
- Medido: Lighthouse `link-name` = 0 en dashboard; C2 midió 2 enlaces sin nombre en la navbar (U2, C2).
- `layouts/navbar.blade.php:5` (pushmenu, `href="#"` role=button) y `:15` (campana; sin `aria-expanded`/`aria-controls`) — C1, C2, X1, X2, U1, U2.
- `auth/login.blade.php:104` botón 👁️ solo emoji, sin `aria-label`/`aria-pressed` — C1, C2, X1, X2, U1. Contraste del botón en oscuro 3.29:1 (C2).
- Botones "mostrar contraseña" solo ícono (≈ 20): `superadmin/{database,email,backup}-configs/{create,edit}.blade.php:94-124`, `oauth-providers/{create:100,edit:94}`, `user/form.blade.php:396,435`, `user/{admin:135,cliente:149,paseador:135}/form.blade.php`, `user/superadmin/show.blade.php:34,156,169,192` — X1 (lista completa), U1 (`database-configs/create.blade.php:94`, `oauth-providers/edit.blade.php:94`).
- Acciones CRUD solo ícono (eliminar/aprobar…): `raza/index.blade.php:80` (U1, patrón en ~20 índices), `barrio/index.blade.php:92`, `cliente/index.blade.php:94`, `departamento/index.blade.php:94`, `mascota/index.blade.php:101`, `document-requirements/index.blade.php:79,89`, `mascota-documents/index.blade.php:132`, `superadmin/oauth-providers/index.blade.php:111,153` — X1, U1.
- `btn-close` sin `aria-label`: `document-requirements/index.blade.php:30,37`, `mascota-documents/index.blade.php:56`, `modules/all-logs.blade.php:209`, `visual-simulator.blade.php:62,115` — X1, U1. Íconos decorativos sin `aria-hidden` (patrón global) — U1, X1.
- **Fix propuesto:** `aria-label` contextual ("Eliminar raza X"), `aria-pressed` en toggles, `aria-hidden="true"` en íconos decorativos, `<button>` en vez de `<a href="#">`.

### FE-03 · Sin `<main>` ni skip link — Alta · ACC · C1, C2, X1, X2, U1, U2
- `layouts/app.blade.php:44` (inicio de `<body>`, sin skip link) y `:66` (`<section class="content">` en lugar de `<main>`) — X1, X2, U1; C2 midió "landmark `<main>`/skip link ausentes" en login, dashboard y razas; U2 y C1 igual. Solo `welcome.blade.php:61` usa `<main>` (U1).
- **Fix:** `<a href="#contenido-principal">Saltar al contenido</a>` + `<main id="contenido-principal" tabindex="-1">`.

### FE-04 · Navegación rota por rol — Alta · VIS/ACC · C1, C2, X1, X2, U1, U2 (+ ampliación ✔)
- **Sidebar de Admin desactivado**: `layouts/sidebar.blade.php:11-13` (`{{-- @include('admin.sidebar') --}}`) — X2 (P0 #2), U2.
- ✔ **Ampliación verificada para este informe:** el layout solo incluye menús para Admin (comentado), Cliente y Superadmin (`sidebar.blade.php:15-21`). **`paseador/sidebar.blade.php` existe (19 líneas) pero nunca se incluye → el rol Paseador tampoco tiene navegación lateral.** Ninguna auditoría lo señaló; hay que confirmarlo en navegador con un usuario Paseador.
- `href="#"` sin destino: `navbar.blade.php:98` ("Perfil") — C2, X1, X2, U1; `app.blade.php:76` y `footer.blade.php:5` — X1, X2, U1, C1; sidebar de Superadmin `:44,75,95,105,164,173,179` (submenús; deberían ser `<button aria-expanded>`) — X1, C1.
- `navbar.blade.php:103` "Cerrar sesión" es enlace **GET** (existe `Route::get('/logout')`, `routes/web.php:106`) — U1 (única fuente; ver §4).
- **Fix:** matriz rol→menú (Admin, Paseador), retirar/implementar enlaces nulos, prueba de render por rol.

### FE-05 · Títulos y jerarquía de encabezados — Alta · ACC/VIS · C2, X2, U1, U2
- ✔ `layouts/app.blade.php:9` usa `@yield('template_title', …)` y `:59` imprime `<h1>@yield('template_title')</h1>`; **100 vistas** definen `template_title`, pero **6 definen `@section('title')`** (`admin|cliente|paseador|superadmin/dashboard.blade.php:3`, `dashboard.blade.php:3`, `cliente/verificacion-datos.blade.php`) → `<h1>` vacío y `<title>` genérico en esas pantallas (U1 las marca *critical*).
- Dos `<h1>` por página (layout + vista): `dashboard.blade.php:6` (U1), medido en dashboard: `H1,H1,H3` (C2); `cliente/dashboard.blade.php:48` `<h4>` antes del `<h1>` (U1); `heading-order` falla en Lighthouse (U2). `welcome.blade.php` sin `<h1>` (U1).
- **Fix:** unificar a `template_title` (o `@section` único) y un solo `<h1>` por página.

### FE-06 · Auth fuera del layout, sin sistema visual común — Alta · VIS/DEP · C1, C2, X2, U1, U2
- 5 pantallas son HTML completo (`auth/login|register|reset-password|forgot-password|passwords/email`): login con Bootstrap **5.3.0** (`login.blade.php:8`) vs layout 5.3.2; registro con CSS embebido y **Arial** (`register.blade.php:9`) vs Source Sans Pro del panel (`app.blade.php:27`); `forgot-password` sin CSS; `passwords/email.blade.php` huérfana (C1) — mantenimiento ×5.
- Errores sin `aria-describedby`/`aria-invalid` (`register.blade.php:147-173`, X2), en lista global y no inline (`login.blade.php:80`, U1).
- **Fix:** `layouts/guest.blade.php` compartido + componentes de formulario.

### FE-07 · `forgot-password`: sin estilos, `lang="en"`, sin feedback — Alta · ACC/VIS · C1, C2, U1 (✔ verificado `lang="en"`)
- 18 líneas, sin CSS: **medido en navegador** — HTML por defecto; correo inexistente → **0 mensajes** de error/estado (C2 H-01). `forgot-password.blade.php:2` `lang="en"` con texto en español (U1 ✔), `:13` sin `autocomplete`/`old()` (U1, C1).

### FE-08 · Imágenes `asset('public/storage/…')` → 404 — Alta · VIS · C1, C2, U2 (medido)
- `curl`: `/public/storage/img/logo.jpg` **404**, `/storage/img/logo.jpg` **200** (C2); Lighthouse `errors-in-console` (U2, "Critical"). Archivos: `auth/login.blade.php:17`, `layouts/sidebar.blade.php:5`, `layouts/navbar.blade.php:50-69`, `cliente/dashboard.blade.php:14-29`, `mascota/show.blade.php:21` (C2, U2). Logos/avatares rotos en login, dashboard y razas (3/3, 2/2).
- **Fix:** `asset('storage/…')`/`Storage::url()`.

### FE-09 · Foco visible — Alta · ACC · C1, X1, U1 (C2 y X2 como "verificar")
- `input:focus { outline:none }` solo con cambio de borde: `auth/register.blade.php:50`, `auth/reset-password.blade.php:50`, `auth/passwords/email.blade.php:50`. Sin `:focus-visible` propio en todo el proyecto (0 coincidencias, C1). `welcome.blade.php:36` sí usa `focus-visible:ring` (aceptable, U1).

### FE-10 · Tema oscuro roto — Alta · VIS/ACC · X2 (P0 #1), X1, U1, U2, C1, C2
- Tres mecanismos: `layouts/app.blade.php:119-129` pone `data-theme` en `<html>`; `resources/js/app.js:8-25` aplica la clase `dark-theme`; `resources/css/app.css:5` la define; `public/css/app.css:8-10` usa `[data-theme="dark"]` y declara **`--text-color: #0000` (transparente)** (X2). Bootstrap 5.3 espera `data-bs-theme` (C1). `app.js` no importa `app.css` y el layout no lo pide (X2; ✔ coincide con §2.1).
- Sin `color-scheme: dark` ni `<meta name="theme-color">` (`app.blade.php:6`) — X1, U1, C1, `resources/css/app.css:5`; `app.js:10,33`: la preferencia del sistema se guarda como elección explícita y el cambio del sistema pisa la elección manual (X1).
- Efecto medido: en oscuro el login muestra enlaces `#0d6efd` sobre `#212529` (3.43:1) (C2).
- **Fix:** un solo contrato (p. ej. `data-bs-theme`), tokens en una hoja importada por Vite, `color-scheme`, test de contraste en ambos temas.

### FE-11 · Contraste — Alta · ACC · C2 (medido), U2 (Lighthouse)
- Login: enlaces "¿Olvidaste…?"/"¿Regístrate?" 3.43:1 (<4.5:1); dashboard: nombre de usuario en navbar, "Acciones Rápidas", footer (U2). A11y: login 92, dashboard 80.

### FE-12 · Controles sin `<label>` asociado — Alta · ACC · X1, U1 (+C1 placeholder)
- `barrio/form.blade.php:15-22` (usa `<strong>`, sin `for`), `ciudade/form.blade.php:5,17`, `empresa/form.blade.php:12,21,33,44,107,117`, `mensaje-de-bienvenida/form.blade.php:6`, `tipo-documento/form.blade.php:6`, `tipos-empresa/form.blade.php:6`, `livewire/modules/toggle-button.blade.php:13`, `modules/index.blade.php:35,38,90` (búsqueda/filtro/código) — X1 (lista), U1 (`barrio/form:15`, `modules/index:35,38,90`).

### FE-13 · Depuración en producción (login) — Media · SEG · C1, C2, X1, X2, U1, U2
- `auth/login.blade.php:135-190` (X2 cita `:135-164`; X1 `:135,164`) registra correo y longitud de contraseña; 15 `console.log` en vistas (C1/C2); `public/js/app.js:41` (X1, U1). Confirmado en consola en vivo (C2).

### FE-14 · `autocomplete`/`spellcheck` y credenciales — Media · ACC/SEG · C1, C2, X1, U1
- Auth: `login.blade.php:96,103`, `register.blade.php:148-172`, `reset-password.blade.php:104-110`, `passwords/email.blade.php:92`, `forgot-password.blade.php:13` — 10 campos sin `autocomplete`.
- Config sensibles: `email-configs/create.blade.php:86,97,113`, `database-configs/create.blade.php:93`, `backup-configs/create.blade.php:122` (+edit) — sin `autocomplete="off"/new-password` (el gestor de contraseñas del navegador ofrece credenciales de otro sistema) — C1, U1; username SMTP como `type="text"` (U1).
- `empresa/form.blade.php:107,117` sin `autocomplete="tel|email"` — C1, X1.

### FE-15 · `aria-live` y toasts — Media · ACC · X1, X2, U1
- `layouts/app.blade.php:89-109`: toasts SweetAlert2 de 3000 ms, también errores, sin región viva (X2 #11, X1 `:89,:93`, U1). Actualizaciones asíncronas sin `aria-live`: `modules/index.blade.php:95,172`, `configuracion/index.blade.php:166`, `superadmin/{database-configs/index:178,email-configs/index:235,oauth-providers/index:222}`, `livewire/modules/toggle-button.blade.php:2`, `verify-email.blade.php:11` — X1, U1.

### FE-16 · DataTables y terceros repetidos — Media · RND/DEP · C2, X2, U2
- 14–15 vistas (`vacunas_certificaciones/index.blade.php:9-11,120-131`, …) re-cargan DataTables+Buttons+pdfmake+jszip; `/razas` 685 KB (C2, X2, U2).

### FE-17 · Estilos y comportamiento en línea — Media · VIS/DEP · C1, C2, X2, U2
- 239 `style=""` (más concentrados: `mascota/show.blade.php` 40, `user/cliente/show.blade.php` 27, `auth/register.blade.php` 16, `cliente/arbol-genealogico.blade.php` 16 — X2), 39 `onclick=`, bloques `<style>` en 8+ vistas; dos `<div onclick>` (`visual-simulator.blade.php:68,85`) — C1, X1, U1 (críticos de teclado).

### FE-18 · Animación — Media · ACC/RND · C1, X1, U1
- `transition: all` en `clean/index.blade.php:12`, `configuracion/index.blade.php:12`, `migration/index.blade.php:12`, `superadmin/dashboard.blade.php:70`, `visual-simulator.blade.php:306,562`; 0 `prefers-reduced-motion` (`app.blade.php:44` `hold-transition`, `clean/index:15`, `arbol-genealogico.blade.php:132`, `welcome.blade.php:66`).

### FE-19 · Imágenes — Media · RND/ACC · C1, C2, X1, X2, U1
- Sin `width/height` (CLS): `navbar.blade.php:84`, `sidebar.blade.php:5`, `login.blade.php:17`, `cliente|admin|paseador|superadmin/dashboard`, `mascota/show.blade.php:44`, `welcome.blade.php:24`, `user/form.blade.php:499`, `visual-simulator.blade.php:69,191`, etc. (X1, U1); 11–13 `<img>` sin `alt` (C2 midió 11/25, X2 contó 13: difieren en método de conteo; `empresa/form|pdf|show`, `mascota/form`, `mensaje-de-bienvenida/index|show`, `user/show`…); 38 sin `loading="lazy"` (C1).

### FE-20 · Registro/login se desbordan en pantallas bajas — Media · VIS · C1, X1, X2
- `register.blade.php:16` `height:100vh` (X1, X2); `login.blade.php:10` `vh-100` (C1). Usar `min-height:100dvh` + scroll.

### FE-21 · `\r\n\r\n` literal en el mensaje de bienvenida — Media · CNT · C2, U2 (ambos en navegador)
- Dashboard del Superadmin; corregir en la vista/controlador (`nl2br`/`Str::markdown`) o en el dato (seeder).

### FE-22 · Cabeceras de seguridad, SRI, CSP — Media · SEG · C2, U2, C1
- Sin CSP, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, y `X-Powered-By: PHP/8.3.33` expuesto (C2, `curl -I`). Sin `integrity` en ~246 referencias CDN (C2, U2). CSP además choca con FE-17 (inline).

### FE-23 · Marca, copy e i18n — Baja · CNT · C1, U1, U2, X1
- "ModuStackPetLTE"/"Laravel" en títulos (U2); `paseador/dashboard.blade.php:3` dice "Dashboard Cliente" (U1); `__('Create'|'Show'|…)` y confirm en inglés (`tipo-documento/index:57`, `tipos-empresa/index:57`) (C1, U1); fechas con formato fijo en ≥ 12 vistas: `ciudade/show.blade.php:37,41`, `departamento/show.blade.php:47,52`, `cliente/index.blade.php:78`, `paths-documentos/index.blade.php:77` … (C1, U1).

### FE-24 · SEO mínimo — Baja · CNT · C2, U2
- Sin `<meta name="description">` (Lighthouse SEO 92); `robots.txt` con `Disallow:` vacío en un panel privado.

### FE-25 · Menores — Baja · C1, X1, U1, U2
`target="_blank"` sin `rel="noopener"` (`oauth-providers/index.blade.php:417` X1; C2); `...`→`…` (`arbol-genealogico.blade.php:48`, `oauth-providers/index:195/222`, `visual-simulator:171`; C1, X1, U1); `touch-action: manipulation` y `<select>` en tema oscuro (`resources/css/app.css:5`; X1, U1); `alert()`/`confirm()` nativos ×21 (C1; `visual-simulator.blade.php:664` U1, C1).

## 4. Hallazgos únicos de una sola auditoría (no se pierden)

| Fuente | Hallazgo | Archivo:línea | Sev. | Tipo |
|---|---|---|---|---|
| **U2** (Lighthouse) | `aria-required-children`, `listitem` fallan en el sidebar AdminLTE | dashboard | Alta | ACC |
| U2 | Perf login 96 / dashboard 69; A11y 92/80; SEO 92 (línea base para re-medir) | — | info | RND |
| U2 | Emoji en títulos de producto (`cliente/dashboard.blade.php`) | — | Baja | VIS |
| **U1** | Toggle de estado = `<span class="status-switch">` clicable sin teclado | `paths-documentos/index.blade.php:70` | **Alta** | ACC |
| U1 | `public/js/bootstrap.js` hace `import axios` como script clásico (puede fallar) | `public/js/bootstrap.js:1` | Media | DEP |
| U1 | Logout por GET (sin POST/confirm) | `navbar.blade.php:103` | Media | SEG |
| U1 | Cambio de estado AJAX sin confirmación/undo; toggles OAuth/limpieza sin confirmar | `paths-documentos/index:159`, `oauth-providers/index:140`, `clean/index:73` | Media | ACC |
| U1 | Modales sin `overscroll-behavior`; empty states sin `@forelse` | `database-configs/index:165`, `modules/index:62` | Media | VIS |
| U1 | `autofocus` en formularios; placeholders sin `…` | `departamento/form:12`, `sectore/form:6` | Baja | ACC |
| U1 | SVG del árbol sin `role="img"`/`aria-label` | `arbol-genealogico.blade.php:42` | Baja | ACC |
| **X1** | Error del árbol genealógico cargado en SVG no se anuncia | `arbol-genealogico.blade.php:158` | Alta | ACC |
| X1 | Al revelar el campo de verificación no se mueve el foco | `modules/index.blade.php:171` | Media | ACC |
| X1 | Persistencia del tema: la preferencia del sistema se guarda como elección explícita | `resources/js/app.js:10,33`, `public/js/app.js:32` | Media | VIS |
| **X2** | `public/css/app.css:8-10` define `--text-color: #0000` (texto invisible si se carga esa hoja) | ya en FE-10 | Alta | VIS |
| X2 | Criterios de aceptación y plan en 3 fases (se integran en §7) | — | — | — |
| **C2** | Sin cabeceras de seguridad HTTP, `X-Powered-By` (ya FE-22) | `curl -I` | Media | SEG |
| C2 | Warning "Vite manifest no encontrado" en cada carga del panel | consola | Baja | DEP |
| C2 | LCP no medible en el entorno (sin elemento candidato); CLS 0.032 en dashboard | — | info | RND |
| **C1** | `login.blade.php:10` `vh-100`; credenciales SMTP/BD vs. gestor de contraseñas (ya FE-14/20) | — | Media | ACC |

(Los demás hallazgos únicos menores —p. ej. los 135 puntos de U1 por índice CRUD— se agrupan por patrón en FE-02, FE-12, FE-15, FE-19 y FE-23 y siguen siendo válidos línea por línea en el archivo de origen.)

## 5. Qué está bien (consenso)
`lang="es"` en el layout; viewport sin bloquear zoom; sin `onpaste` bloqueado; tablas en `.table-responsive` y DataTables en español; labels con `for/id` en auth; CSRF presente; confirmación de borrado con SweetAlert2 en varios CRUD; `preconnect`+`display=swap` en Google Fonts; **CLS ≈ 0 y sin long tasks**; login ligero (72 KiB, LCP 2.3 s); estructura de parciales (navbar/sidebar/footer) como buena base. *(C1, C2, X1, X2, U1, U2)*

## 6. Discrepancias entre auditorías y cómo se resolvieron
| Tema | Qué dice cada una | Resolución |
|---|---|---|
| **Tailwind en el panel** | La tarea y U2/X2 hablan de "Tailwind + AdminLTE/Bootstrap"; C2 duda de si se usa | ✔ Verificado: configurado, **no servido** en `layouts/app`; solo `welcome` (§2.1) |
| Severidad de `<h1>` vacío | U1 *critical*; C2 lo cuenta como "2 h1" (media) | **Alta** (afecta título y navegación en 6 pantallas clave) |
| Tema oscuro | X2 **P0**; C1 media | **Alta** (texto transparente + 3 mecanismos) |
| Imágenes 404 | U2 *Critical*; C2 alta | **Alta** (sin impacto de seguridad; marca rota) |
| `<img>` sin `alt` | C2: 11/25 (por línea); X2: 13 | Mismo hallazgo; difieren en conteo. Cifra a re-medir con axe |
| Admin sin sidebar | X2, U2 solo Admin | ✔ **Paseador también** (§FE-04) |
| Peso/LCP | U2: dashboard 504 KiB, LCP 5.1 s (lab móvil Lighthouse); C2: 242 KB, load 840 ms (local, sin throttling) | No contradictorio: distinto entorno/throttling. Para metas usar **U2 (Lighthouse móvil)** |
| Contraste | C2 lo midió solo en tema oscuro; U2 en el dashboard | Ambos válidos; ampliar a todas las páginas con axe |

## 7. Plan de acción sugerido por fases (no implementado)

Cada fase termina con **re-medición** (Lighthouse móvil `/login` y `/superadmin/dashboard`, `/razas`; axe) comparada con la línea base: Perf 96/69, A11y 92/80, LCP 2.3/5.1 s, 504 KiB.

### Fase 0 — Decisiones humanas (bloqueante, sin código)
1. **Base de UI** (FE-01): AdminLTE 4 (Bootstrap 5) / AdminLTE 3 con Bootstrap 4 / Tailwind + componentes. Recomendación: la que permita **una sola versión de Bootstrap** y retirar jQuery si es posible.
2. ¿Se mantiene Tailwind? (hoy solo la landing). ¿Landing y panel comparten sistema?
3. Roles Admin y Paseador: ¿qué menú ven? (FE-04).
4. Tema oscuro: ¿se ofrece? (FE-10) Si no, quitar el script.
5. ¿Instalar Lighthouse/axe en el repo (devDependencies) para CI de frontend? (coordinar con la tarea de CI).

### Fase 1 — Correcciones rápidas, bajo riesgo, alto impacto (1–2 tareas pequeñas)
- FE-08 rutas `asset('storage/…')`; FE-13 quitar `console.log`; FE-21 `\r\n`; FE-05 `template_title`/único `<h1>`; FE-07 estilizar `forgot-password` + `lang="es"` + mensajes; FE-04 incluir sidebar de Admin y Paseador y retirar `href="#"` muertos; FE-02 `aria-label` en navbar y botones "mostrar contraseña" (patrón único); FE-03 `<main>` + skip link; FE-09 `:focus-visible` global; FE-11 contraste de enlaces.
- Criterio: `link-name` y `heading-order` pasan; Console sin 404; A11y dashboard ≥ 90.

### Fase 2 — Base de assets (la causa raíz, FE-01/16/22) — tras la decisión de Fase 0
- Declarar todas las dependencias de UI en `package.json`, fijar versiones, compilar con Vite (CSS+JS, `@vite` con ambas entradas en el layout), generar `public/build` en despliegue, **retirar CDN** y los duplicados de `public/js|css`.
- DataTables/pdfmake/jszip como entradas por página (`@stack`), no globales; subset de Font Awesome; SRI no necesario si se autoalojan.
- Cabeceras de seguridad (middleware) y `expose_php=Off` (FE-22); CSP en modo *report-only* primero.
- Meta de rendimiento: dashboard LCP < 2.5 s, transferencia < 250 KiB, unused CSS < 40 KiB.

### Fase 3 — Layout de invitado y formularios (FE-06/12/14/15/20)
- `layouts/guest.blade.php`; componentes Blade de formulario (label, ayuda, error inline con `role="alert"`, `autocomplete`, `spellcheck`); `aria-live` para toasts y resultados asíncronos; no autocerrar errores; `min-height:100dvh`.

### Fase 4 — Sistema visual (FE-10/17/18/19/23)
- Un solo contrato de tema y tokens (color, tipografía, espaciado) con prueba de contraste; migrar primero los 4 archivos con más `style=""`; `transition` explícito + `prefers-reduced-motion`; `width/height/alt/lazy` en imágenes; textos en español, fechas localizadas, marca unificada.

### Fase 5 — Tablas, acciones y robustez (FE-02 resto, FE-12 resto, únicos de §4)
- `scope`/`caption`/`aria-label` en acciones CRUD (≈ 20 índices); `<button>` real en `status-switch` y `visual-simulator`; logout por POST; confirmaciones con consecuencia concreta.

### Fase 6 — Verificación continua
- Lighthouse móvil + axe sobre rutas representativas por rol (Superadmin, Admin, Cliente, Paseador); pasada manual con teclado y lector de pantalla; pruebas de render por rol y de contraste en ambos temas; (opcional) job de CI.

### Criterios de aceptación globales (de X2/U2)
Sin `href="#"` muertos; todos los controles con nombre/estado accesible; todas las `<img>` con `alt`; skip link y `<main>`; foco visible; contraste 4.5:1/3:1; un solo mecanismo de tema; sin scroll horizontal a 320 px; mensajes importantes no desaparecen antes de poder actuar; **Perf dashboard ≥ 90, A11y ≥ 95, LCP < 2.5 s**.

## 8. Limitaciones
- Análisis combinado de fuentes con métodos distintos: solo C2 y U2 midieron en vivo (entorno local, sin red real, sin CrUX); las otras cuatro son estáticas. No se ejecutó nada nuevo salvo las verificaciones puntuales en código indicadas con ✔ (`app.blade.php`, `package.json`/lock, `vite.config.js`, `welcome.blade.php`, `sidebar.blade.php`, `forgot-password.blade.php`, uso de `title`/`template_title`).
- Falta confirmar en navegador con usuarios Admin y Paseador (FE-04), contraste en todas las pantallas y tema oscuro completo, y re-medir tras cada fase.
- Los conteos (alt, `style`, `onclick`) varían ligeramente entre auditorías por el método de búsqueda; usar axe/grep estandarizado como referencia al re-medir.
