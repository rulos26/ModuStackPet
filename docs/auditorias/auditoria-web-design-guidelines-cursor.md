# Auditoría Web Interface Guidelines — ModuStackPet

- **Skill:** `web-design-guidelines` (Vercel Labs / agent-skills)
- **Fuente de reglas:** https://raw.githubusercontent.com/vercel-labs/web-interface-guidelines/main/command.md (consultada en la auditoría)
- **Alcance:** `resources/views/**/*.blade.php`, `resources/css/app.css`, `resources/js/*`, `public/js/*`
- **Método:** solo lectura de código fuente; evaluación independiente
- **Formato:** `archivo:línea` · severidad · hallazgo · recomendación

Severidades: `critical` | `high` | `medium` | `low`

---

## Resumen

| Severidad | Cantidad orientativa |
|-----------|---------------------:|
| critical  | 5 |
| high      | ~35 |
| medium    | ~55 |
| low       | ~40 |

Prioridad sugerida: (1) controles icon-only / `<div onclick>` sin teclado, (2) foco `outline: none` en auth, (3) `template_title` / jerarquía de headings, (4) `transition: all` + `prefers-reduced-motion`, (5) formularios (`label`/`autocomplete`/`aria-live`).

No se encontró `user-scalable=no` ni `maximum-scale=1` en el alcance.

---

## Patrones transversales

Estos hallazgos se repiten en muchos archivos; se documenta un ejemplo canónico y la lista de repeticiones.

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/raza/index.blade.php:80` | high | Botones/enlaces solo icono con `title` y sin `aria-label`. | Añadir `aria-label` descriptivo. Repetido en índices CRUD: `barrio`, `departamento`, `mascota`, `sectore`, `cliente`, `user`, `user/admin`, `user/cliente`, `user/paseador`, `user/superadmin`, `ciudade`, `paths-documentos`, `document-requirements`, `superadmin/oauth-providers`, `superadmin/database-configs`, `superadmin/email-configs`, `superadmin/backup-configs`, `vacunas_certificaciones`, etc. |
| `resources/views/departamento/show.blade.php:47` | medium | Fechas con `->format('d/m/Y…')` fijo (no locale/`Intl`). | Usar locale Carbon / `Intl.DateTimeFormat`. También en `cliente/index`, `user/index`, `paths-documentos/index:77`, `modules/logs`, `modules/all-logs`, `mascota/show`, `user/cliente/show`, `vacunas-certificaciones/show`, `superadmin/backup-configs/logs`, `superadmin/oauth-providers/test-results`. |
| `resources/views/clean/index.blade.php:12` | medium | `transition: all` (anti-patrón). | Listar propiedades (`box-shadow`, `transform`, …). También: `migration/index.blade.php:12`, `configuracion/index.blade.php:12`, `superadmin/dashboard.blade.php:70`, `superadmin/oauth-providers/visual-simulator.blade.php:306,562`. |
| `resources/views/clean/index.blade.php:15` | medium | Hover con `transform` sin `prefers-reduced-motion`. | Desactivar animación bajo `@media (prefers-reduced-motion: reduce)`. No hay usos de esa media query en `resources/views/**`. |
| `resources/views/modules/index.blade.php:62` | medium | `@foreach` de tablas sin rama vacía visible. | `@forelse` / mensaje + CTA. Muchos índices CRUD no vacían la UI cuando no hay filas. |
| `resources/views/empresa/form.blade.php:14` | low | Placeholders sin elipsis tipográfica `…` ni patrón de ejemplo. | Terminar placeholders con `…` (`Nombre legal…`). Amplio en formularios y filtros. |
| `resources/views/cliente/arbol-genealogico.blade.php:50` | low | Estados de carga con `...` ASCII. | Usar `…` (`Cargando…`). También en `visual-simulator`, `database-configs/index`, `email-configs/index`, `oauth-providers/index`, `backup-configs/logs`. |
| `resources/views/mascota/show.blade.php:44` | medium | `<img>` sin `width`/`height` HTML (solo CSS). | Atributos explícitos + `loading="lazy"` below-fold. También logos/avatares en dashboards, forms y `visual-simulator`. |
| `resources/views/superadmin/database-configs/index.blade.php:165` | medium | Modales sin `overscroll-behavior: contain`. | Aplicar en `.modal-body` / overlays. También modales OAuth/email y overlay del visual-simulator. |
| `resources/views/modules/index.blade.php:172` | medium | Updates async (`innerHTML`) sin `aria-live`. | Contenedor `aria-live="polite"`. También resultados de prueba BD/correo/OAuth. |
| `resources/views/raza/index.blade.php:22` | low | Iconos Font Awesome decorativos sin `aria-hidden="true"`. | Marcar `<i>` cuando el texto ya nombra la acción. Patrón global AdminLTE. |

---

## Layouts

### `resources/views/layouts/app.blade.php`

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/layouts/app.blade.php:59` | critical | `<h1>@yield('template_title')</h1>` queda vacío cuando las vistas definen solo `@section('title')`. | Unificar: `@yield('template_title', …)` o migrar vistas a `template_title`. |
| `resources/views/layouts/app.blade.php` (sin skip link) | high | No hay skip link al contenido principal. | `<a class="skip-link" href="#main-content">Saltar al contenido</a>`. |
| `resources/views/layouts/app.blade.php:66` | medium | Contenido en `<section class="content">` sin `<main>`. | Envolver en `<main id="main-content">`. |
| `resources/views/layouts/app.blade.php:44` | medium | `hold-transition` anima sin respetar `prefers-reduced-motion`. | Media query que desactive transiciones AdminLTE. |
| `resources/views/layouts/app.blade.php:17-22` | low | CDNs (jsdelivr, cdnjs, jQuery) sin `preconnect`. | `rel="preconnect"` a dominios críticos (además de Google Fonts ya presentes en :12-14). |
| `resources/views/layouts/app.blade.php:89-109` | high | Toasts SweetAlert2 de sesión sin región `aria-live` en DOM. | Contenedor `role="status"` / `aria-live="polite"` o API a11y de Swal. |
| `resources/views/layouts/app.blade.php:119-129` | low | Tema vía `data-theme` en `<html>`; `app.css`/`app.js` usan clase `dark-theme`. | Unificar mecanismo. |
| `resources/views/layouts/app.blade.php:76` | low | Copyright con `href="#"`. | URL real o `<span>`. |

### `resources/views/layouts/navbar.blade.php`

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/layouts/navbar.blade.php:5` | high | Pushmenu: enlace solo icono, sin `aria-label`; icono sin `aria-hidden`. | `aria-label="Abrir o cerrar menú"`; preferir `<button type="button">`. |
| `resources/views/layouts/navbar.blade.php:15` | high | Campana de notificaciones sin `aria-label` ni `aria-expanded`/`aria-controls`. | Etiqueta con conteo; atributos ARIA de dropdown. |
| `resources/views/layouts/navbar.blade.php:23` | medium | Ítems de notificación como `<li class="dropdown-item">` sin control enfocable. | Usar `<a>`/`<button>` dentro del `<li>`. |
| `resources/views/layouts/navbar.blade.php:84` | medium | Avatar sin `width`/`height` HTML; `alt` genérico. | `width="30" height="30"`; `alt` con nombre de usuario. |
| `resources/views/layouts/navbar.blade.php:98` | medium | “Perfil” con `href="#"`. | Ruta real o deshabilitar con explicación. |
| `resources/views/layouts/navbar.blade.php:103` | high | “Cerrar sesión” es enlace GET sin confirmación (acción de sesión). | Form POST + `@csrf` y/o confirmación. |

### `resources/views/layouts/sidebar.blade.php`

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/layouts/sidebar.blade.php:5` | medium | Logo: dimensiones solo en `style`, no atributos HTML. | `width="30" height="30"`; considerar `fetchpriority="high"` si es LCP. |

### `resources/views/layouts/footer.blade.php`

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/layouts/footer.blade.php:5` | low | Enlace de marca con `href="#"`. | URL HTTPS real. |

---

## Auth

### `resources/views/auth/login.blade.php`

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/auth/login.blade.php:104` | critical | Botón mostrar/ocultar contraseña solo con emoji, sin `aria-label` ni `aria-pressed`. | `aria-label="Mostrar contraseña"` y alternar estado. |
| `resources/views/auth/login.blade.php:96` | medium | Email sin `autocomplete="email"` ni `spellcheck="false"`. | Añadir ambos. |
| `resources/views/auth/login.blade.php:103` | medium | Password sin `autocomplete="current-password"`. | Añadir autocomplete. |
| `resources/views/auth/login.blade.php:80` | medium | Errores en lista global, no inline ni foco al primer error. | `@error` por campo + foco al primero. |
| `resources/views/auth/login.blade.php:17` | medium | Logo sin atributos `width`/`height` HTML. | p. ej. 100×100. |
| `resources/views/auth/login.blade.php:8` | low | Bootstrap CDN sin `preconnect`. | `preconnect` a jsdelivr. |
| `resources/views/auth/login.blade.php:38` | low | SVG OAuth sin `aria-hidden` (texto del botón ya nombra la acción). | `aria-hidden="true"` en SVG. |

### `resources/views/auth/register.blade.php`

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/auth/register.blade.php:50` | high | `input:focus { outline: none; }` sin reemplazo `:focus-visible`. | Anillo visible con `:focus-visible`. |
| `resources/views/auth/register.blade.php:148` | medium | Campos sin `autocomplete` (`name`, `email`, `new-password`). | Completar atributos estándar. |
| `resources/views/auth/register.blade.php:156` | medium | Email sin `spellcheck="false"`. | Añadir. |

### `resources/views/auth/reset-password.blade.php`

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/auth/reset-password.blade.php:50` | high | Mismo `outline: none` sin foco visible. | `:focus-visible` accesible. |
| `resources/views/auth/reset-password.blade.php:104` | medium | Faltan `autocomplete` / `spellcheck` en email y passwords. | `email`, `new-password`, `spellcheck="false"`. |

### `resources/views/auth/passwords/email.blade.php`

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/auth/passwords/email.blade.php:50` | high | `outline: none` sin reemplazo. | Corregir estilos de foco. |
| `resources/views/auth/passwords/email.blade.php:92` | medium | Email sin `autocomplete`/`spellcheck`. | Añadir. |

### `resources/views/auth/forgot-password.blade.php`

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/auth/forgot-password.blade.php:2` | medium | `lang="en"` con textos en español. | `lang="es"`. |
| `resources/views/auth/forgot-password.blade.php:13` | medium | Email sin `autocomplete`/`spellcheck`; sin `old()`. | Añadir atributos y `value="{{ old('email') }}"`. |
| `resources/views/auth/forgot-password.blade.php:8` | medium | Página mínima sin `<main>` ni feedback `@error`. | Alinear con plantilla de login. |

### `resources/views/auth/verify-email.blade.php`

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/auth/verify-email.blade.php` (usa layout) | critical | Extiende `layouts.app` sin `template_title` → `<h1>` vacío; contenido con `<h3>`. | Definir `@section('template_title')` y jerarquía coherente. |
| `resources/views/auth/verify-email.blade.php:11` | medium | Mensaje de reenvío sin `role="status"`/`aria-live`. | Región live. |

---

## Dashboards

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/dashboard.blade.php:3` | high | `@section('title')` no alimenta `template_title` → `<h1>` vacío + título HTML por defecto. | `@section('template_title', 'Dashboard')`. |
| `resources/views/dashboard.blade.php:6` | medium | Segundo `<h1>` en content además del del layout. | Un solo `<h1>` por página. |
| `resources/views/cliente/dashboard.blade.php:3` | high | Mismo desajuste `title` vs `template_title`. | Usar `template_title`. |
| `resources/views/cliente/dashboard.blade.php:48` | high | `<h4>` (nombre) antes de `<h1>` de bienvenida; layout también aporta `<h1>`. | Orden: un `<h1>` principal; nombre como `<p>`/`<h2>`. |
| `resources/views/cliente/dashboard.blade.php:44` | medium | Avatar/logos sin `width`/`height` HTML. | Dimensiones explícitas. |
| `resources/views/admin/dashboard.blade.php:3` | high | `title` sin `template_title`. | Corregir sección. |
| `resources/views/admin/dashboard.blade.php:12` | medium | Logo sin dimensiones HTML. | `width`/`height`. |
| `resources/views/paseador/dashboard.blade.php:3` | medium | Título copy dice “Dashboard Cliente” (incorrecto para paseador). | Texto correcto + `template_title`. |
| `resources/views/paseador/dashboard.blade.php:12` | medium | Imágenes sin dimensiones HTML. | Atributos explícitos. |
| `resources/views/superadmin/dashboard.blade.php:3` | high | `title` vs `template_title`. | `@section('template_title', …)`. |
| `resources/views/superadmin/dashboard.blade.php:70` | high | `style="transition: all 0.3s;"`. | Propiedades explícitas. |
| `resources/views/superadmin/dashboard.blade.php:87` | medium | Hover `transform` sin reduced-motion. | Media query. |

---

## Welcome

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/welcome.blade.php` (cabecera) | high | Sin skip link; primer heading en main es `<h2>` (sin `<h1>`). | Skip link + `<h1>` de producto. |
| `resources/views/welcome.blade.php:24` | medium | Imagen de fondo sin `width`/`height` ni `fetchpriority` si es LCP. | Dimensiones + prioridad si aplica. |
| `resources/views/welcome.blade.php:66` | medium | Transiciones sin variante reduced-motion. | Media query. |
| `resources/views/welcome.blade.php:36` | low | `focus:outline-none` con `focus-visible:ring-*` (aceptable). | Mantener; verificar contraste del anillo. |

---

## CSS / JS

### `resources/css/app.css`

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/css/app.css:5` | medium | `.dark-theme` sin `color-scheme: dark`. | Añadir `color-scheme: dark` en html/tema oscuro. |
| `resources/css/app.css` (global) | low | Sin `touch-action: manipulation` ni reglas `prefers-reduced-motion` base. | Utilidades globales de interacción/motion. |

### `resources/js/app.js`

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/js/app.js:8` | low | Aplica clase `dark-theme`; layout AdminLTE usa `data-theme` — desalineado. | Unificar con layout. |

### `resources/js/bootstrap.js`

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/js/bootstrap.js` | — | Solo Axios; sin hallazgos WIG. | — |

### `public/js/app.js`

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `public/js/app.js:41` | low | `console.log` en fallback público. | Eliminar o condicionar a debug. |
| `public/js/app.js:1` | low | Duplicado lógico de `resources/js/app.js`. | Una sola fuente vía Vite. |

### `public/js/bootstrap.js`

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `public/js/bootstrap.js:1` | medium | `import axios` en archivo servible como script clásico puede fallar. | Compilar con Vite / IIFE; alinear con `@vite`. |

---

## Índices CRUD y módulos

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/paths-documentos/index.blade.php:70` | critical | Toggle de estado es `<span class="status-switch">` clickeable (sin rol/teclado). | `<button type="button">` o `role="switch"` + teclado. |
| `resources/views/paths-documentos/index.blade.php:159` | medium | Cambio AJAX de estado sin confirmación/undo. | Confirmar o deshacer. |
| `resources/views/document-requirements/index.blade.php:79` | high | Acciones Ver/Editar/Eliminar solo icono sin `aria-label`. | Etiquetas accesibles. |
| `resources/views/document-requirements/index.blade.php:30` | medium | `btn-close` de alerta sin `aria-label`. | `aria-label="Cerrar"`. |
| `resources/views/document-requirements/index.blade.php:89` | low | Confirm genérico `¿Está seguro?`. | Mensaje con consecuencia concreta. |
| `resources/views/tipo-documento/index.blade.php:57` | low | Confirm delete en inglés. | Español coherente con la app. |
| `resources/views/tipos-empresa/index.blade.php:57` | low | Mismo confirm en inglés. | Localizar. |
| `resources/views/modules/index.blade.php:35` | medium | Búsqueda solo con `placeholder`, sin `<label>`. | Label (visible o `visually-hidden`). |
| `resources/views/modules/index.blade.php:38` | medium | `<select name="status">` sin etiqueta. | `<label for="status">`. |
| `resources/views/modules/index.blade.php:90` | medium | Input código verificación sin label; placeholder sin `…`. | Label + `autocomplete="one-time-code"` + `…`. |
| `resources/views/modules/index.blade.php:66` | low | Descripción sin truncado/`line-clamp`. | Truncar + `min-w-0` en celdas flex. |
| `resources/views/superadmin/oauth-providers/index.blade.php:111` | high | Botones prueba/simular/eliminar solo icono. | `aria-label` por acción. |
| `resources/views/superadmin/oauth-providers/index.blade.php:140` | medium | Toggle activo sin confirmación. | Confirmar cambio de estado. |
| `resources/views/superadmin/email-configs/index.blade.php:179` | medium | Email de prueba sin `autocomplete="email"`. | Completar atributos aunque el envío sea `fetch`. |
| `resources/views/seeders/index.blade.php:20` | low | Alertas sin dismiss accesible. | `btn-close` + `aria-label` + `role="alert"`. |
| `resources/views/clean/index.blade.php:73` | medium | Acciones de limpieza individuales sin confirmación (solo “Limpiar todo”). | Confirmación por acción sensible. |
| `resources/views/clean/index.blade.php:296` | low | Scroll animado jQuery sin reduced-motion. | Condicionar a `matchMedia('(prefers-reduced-motion: reduce)')`. |
| `resources/views/migration/index.blade.php:72` | low | POST “Ver estado” sin `aria-busy`/spinner accesible. | Feedback de carga. |

---

## Formularios

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/barrio/form.blade.php:15` | high | Campos con `<strong>` en lugar de `<label for>`. | `label.form-label` + `id` en inputs. |
| `resources/views/departamento/form.blade.php:12` | low | `autofocus` en create/edit. | Evitar en móvil; un solo autofocus justificado en desktop. |
| `resources/views/sectore/form.blade.php:6` | low | `autofocus` en campo requerido. | Misma regla. |
| `resources/views/superadmin/database-configs/create.blade.php:94` | high | Toggle mostrar contraseña solo icono sin `aria-label`. | `aria-label` + `aria-pressed`; icono `aria-hidden`. |
| `resources/views/superadmin/database-configs/create.blade.php:48` | medium | Inputs con label pero sin `autocomplete` (patrón en create/edit superadmin y CRUD). | Mapear `autocomplete` / `spellcheck="false"` en secretos. |
| `resources/views/superadmin/email-configs/create.blade.php:86` | medium | Username SMTP como `type="text"`. | Preferir `type="email"` o `autocomplete="username"` + `spellcheck="false"`. |
| `resources/views/superadmin/oauth-providers/edit.blade.php:94` | high | Toggle Client Secret icon-only sin `aria-label`. | Igual que database-configs; `autocomplete="off"` en secreto. |
| `resources/views/modules/verification.blade.php:38` | low | Placeholder `123456` sin `…`; `autocomplete="off"`. | `placeholder="123456…"` + `autocomplete="one-time-code"`. |
| `resources/views/user/paseador/form.blade.php:12` | medium | Placeholders sin `…`; email sin `autocomplete`/`spellcheck` (patrón en `user/*/form`, `empresa/form`, `mascota/form`). | Completar atributos HTML de formulario. |

---

## Show / cliente / modules auxiliares

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/user/cliente/show.blade.php:132` | medium | Fecha nacimiento con formato fijo / inconsistente. | Locale/`Intl` uniforme. |
| `resources/views/document-requirements/show.blade.php:118` | low | Eliminar con confirmación vaga. | Incluir nombre del requisito. |
| `resources/views/modules/access-denied.blade.php:13` | low | Icono cabecera sin `aria-hidden`. | Marcar decorativo. |
| `resources/views/modules/all-logs.blade.php:131` | low | Email/nombre sin truncado. | `text-truncate` / `break-all` controlado. |
| `resources/views/cliente/verificacion-datos.blade.php:9` | medium | `alert-dismissible` sin botón cerrar. | `btn-close` + `aria-label`. |
| `resources/views/cliente/arbol-genealogico.blade.php:42` | low | SVG árbol sin `aria-label`/`role="img"`. | Etiqueta accesible del gráfico. |
| `resources/views/cliente/arbol-genealogico.blade.php:60` | low | Empty state OK, pero contenedor vacío sigue en DOM. | Ocultar `#arbol-container` si `count === 0`. |

---

## Superadmin — visual-simulator OAuth

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:68` | critical | `<div class="account-item" onclick="…">` — solo puntero, sin rol/teclado. | `<button type="button">` o teclado + rol. |
| `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:62` | high | `btn-close` sin `aria-label`. | `aria-label="Cerrar"`. |
| `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:198` | high | Cerrar sesión mock: botón solo icono sin `aria-label`. | Etiqueta accesible. |
| `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:69` | medium | Avatar remoto sin `width`/`height`. | p. ej. 40×40 + `loading="lazy"`. |
| `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:306` | medium | `transition: all 0.2s` (también :562). | Propiedades explícitas. |
| `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:316` | medium | Overlay modal sin `overscroll-behavior: contain`. | CSS en overlay/body. |
| `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:648` | medium | Auto-avance con `setTimeout` sin reduced-motion. | Saltar/acortar animación; botón Continuar. |
| `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:664` | low | `alert()` bloqueante. | Modal in-page con foco. |
| `resources/views/superadmin/oauth-providers/visual-simulator.blade.php:171` | low | “Cargando...” / “Redirigiendo...” con `...`. | Usar `…`. |

---

## Livewire

| Archivo:línea | Severidad | Hallazgo | Recomendación |
|---|---|---|---|
| `resources/views/livewire/modules/toggle-button.blade.php:13` | medium | Input código sin `<label>`; placeholder sin `…`. | Label + `autocomplete="one-time-code"`. |
| `resources/views/livewire/modules/toggle-button.blade.php:2` | medium | `$message` sin `aria-live`. | `role="status"` + `aria-live="polite"`. |
| `resources/views/livewire/menu/modules-menu.blade.php:6` | low | Iconos de menú sin `aria-hidden`. | Ocultar decorativos. |

---

## Aspectos que cumplen (muestra)

- `layouts/app.blade.php:12-14` — `preconnect` Google Fonts + `display=swap`.
- `welcome.blade.php:10-11` — `preconnect` Bunny Fonts + `display=swap`.
- `welcome.blade.php:61` — uso de `<main>`.
- Viewport sin bloquear zoom (`user-scalable`/`maximum-scale` no presentes).
- Varios índices CRUD confirman borrado con SweetAlert2 (`.delete-form`).
- Superadmin BD/correo/OAuth vacíos usan `@forelse` con CTA.
- Muchos `btn-close` ya traen `aria-label="Cerrar"`.
- Formularios auth en general asocian `<label for>` e `input` con `name`/`type` correctos.

---

## Notas de método

- Skill `SKILL.md` inspeccionado antes de usarlo: solo instrucciones (fetch de guidelines + revisión); sin scripts ejecutables.
- Guidelines frescas obtenidas de la URL oficial del skill.
- No se usaron auditorías previas bajo `docs/auditorias/` como base.
- Alcance Blade ~149 vistas; hallazgos críticos/altos se verificaron en archivo; patrones repetidos se consolidaron para evitar ruido.
