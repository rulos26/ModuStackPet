# Auditoría de interfaz — Web Interface Guidelines (Vercel)

Fecha: 2026-10-03 · Agente: Claude · Rama: `ia/claude/auditoria-frontend`

Complementa `docs/auditorias/auditoria-frontend-diseno.md` (web-quality-audit, con mediciones en vivo).

## Skill y método
- Skill: **`web-design-guidelines`** de Vercel Labs, instalada con
  `npx skills add https://github.com/vercel-labs/agent-skills --skill web-design-guidelines`
  (resultado: `.claude/skills/web-design-guidelines/SKILL.md`; evaluaciones de seguridad del instalador: Socket 0 alertas, Snyk riesgo bajo; el archivo es solo texto, sin scripts).
- Procedimiento de la skill: descargar la guía vigente (`vercel-labs/web-interface-guidelines/command.md`), leer los archivos y reportar en formato `archivo:línea`. Se aplicó **la guía completa** (accesibilidad, foco, formularios, animación, tipografía, imágenes, rendimiento, navegación, táctil, tema oscuro, i18n, anti-patrones).
- La guía está pensada para React/Tailwind; se adaptó a Blade/Bootstrap (por ejemplo `onClick` → `onclick=`, `<Link>` → `<a>`). Las reglas de *hydration* y de `useState`/URL no aplican (app renderizada en servidor).
- **Análisis estático** sobre `resources/views`, `resources/css`; sin ejecutar la app en esta pasada (las mediciones en vivo están en el otro informe). Los números de línea son del commit `60e8debd`. Lista no exhaustiva donde se indica "(+N más)".
- Solo lectura: no se modificó código de aplicación ni `.env`.

## Hallazgos por archivo

### resources/views/auth/forgot-password.blade.php
forgot-password.blade.php:1-18 - página sin ninguna hoja de estilos (HTML por defecto); sin card, sin foco estilizado
forgot-password.blade.php:13 - input email sin `autocomplete="email"` ni `spellcheck="false"`
forgot-password.blade.php:10 - sin mensaje de estado/errores (`aria-live`, errores inline) tras enviar
forgot-password.blade.php:- - botón de envío con texto genérico ("Enviar enlace"): usar "Enviar enlace de recuperación"
forgot-password.blade.php:- - sin `<main>`, sin skip link

### resources/views/auth/login.blade.php
login.blade.php:10 - `vh-100` en `body` con `d-flex`: recorta/obliga a scroll en móvil con teclado abierto (usar `min-vh-100` + padding)
login.blade.php:17 - logo con ruta `asset('public/storage/...')` (404) y `style` en línea; sin `width`/`height` en atributos
login.blade.php:96 - input email sin `autocomplete="username"`/`email`, sin `spellcheck="false"`
login.blade.php:103 - input password sin `autocomplete="current-password"`
login.blade.php:104 - botón solo emoji 👁️ sin `aria-label`/`aria-pressed`; `onclick` en línea
login.blade.php:140 - `togglePassword()` global; el botón debería alternar `aria-pressed` y el texto
login.blade.php:150-190 - `console.log` de depuración con datos del formulario
login.blade.php:194-213 - lógica de tema oscuro duplicada; no hay `color-scheme` ni `theme-color`
login.blade.php:- - submit no se deshabilita/spinner durante la petición ("Iniciar Sesión…")
login.blade.php:- - sin foco automático al primer error al enviar

### resources/views/auth/register.blade.php
register.blade.php:50 - `input:focus { outline: none }` con solo cambio de `border-color` como reemplazo (insuficiente; usar `:focus-visible` con anillo)
register.blade.php:156 - email sin `autocomplete="email"`
register.blade.php:164 - password sin `autocomplete="new-password"`
register.blade.php:172 - confirmación sin `autocomplete="new-password"`
register.blade.php:- - documento HTML propio con `<style>` embebido (fuera del layout)

### resources/views/auth/reset-password.blade.php
reset-password.blade.php:50 - `outline: none` sin reemplazo robusto de foco
reset-password.blade.php:104 - email sin `autocomplete="email"` (y es de solo lectura funcional: considerar `readonly`)
reset-password.blade.php:107,110 - passwords sin `autocomplete="new-password"`

### resources/views/auth/passwords/email.blade.php
email.blade.php:50 - `outline: none` sin reemplazo robusto de foco
email.blade.php:92 - email sin `autocomplete`
email.blade.php:- - vista huérfana (la ruta usa `auth/forgot-password`): duplicada

### resources/views/layouts/navbar.blade.php
navbar.blade.php:5 - enlace del menú (`pushmenu`) solo ícono sin `aria-label`; `href="#"` con `role="button"` (usar `<button>`)
navbar.blade.php:15 - campana de notificaciones solo ícono sin `aria-label`; `href="#"`; el contador (`badge`) sin texto accesible
navbar.blade.php:47 - disparador del menú de usuario con `href="#"`; sin nombre accesible más allá del texto
navbar.blade.php:85 - `<img>` sin `width`/`height` (usa `style`)
navbar.blade.php:98 - ítem de dropdown con `href="#"` (acción sin destino)
navbar.blade.php:5/15 - mezcla `data-widget` (AdminLTE 3) y `data-bs-toggle` (Bootstrap 5)

### resources/views/layouts/app.blade.php
app.blade.php:~60 - sin `<main>` ni skip link ("Saltar al contenido")
app.blade.php:~58 - `<h1>@yield('template_title')</h1>` duplica el `<h1>` de cada página
app.blade.php:76 - `<a href="#">` en el pie
app.blade.php:81-84 - scripts de CDN bloqueantes sin `defer`; sin `integrity`/`crossorigin`
app.blade.php:119-128 - tema por `data-theme`, pero Bootstrap 5.3 usa `data-bs-theme`: el atributo no tiene efecto; falta `color-scheme`
app.blade.php:6 - falta `<meta name="theme-color">`
app.blade.php:- - sin `touch-action: manipulation` ni reglas `prefers-reduced-motion`

### resources/views/layouts/footer.blade.php
footer.blade.php:5 - `<a href="#">` como enlace placeholder

### resources/views/layouts/sidebar.blade.php
sidebar.blade.php:5 - logo con ruta `public/storage/...` (404), alt "Logo de la empresa", sin dimensiones

### resources/views/superadmin/sidebar.blade.php
sidebar.blade.php:44,75,95,105,164,173 (+N más) - enlaces con `href="#"` como cabeceras de submenú: usar `<button>` con `aria-expanded`/`aria-controls`

### resources/views/superadmin/oauth-providers/visual-simulator.blade.php
visual-simulator.blade.php:68 - `<div ... onclick="selectAccount()">` → `<button>`
visual-simulator.blade.php:85 - `<div ... onclick="showLoginForm()">` → `<button>`
visual-simulator.blade.php:306,562 - `transition: all` → listar propiedades
visual-simulator.blade.php:171 - "Cargando..." → "Cargando…"
visual-simulator.blade.php:664 - `alert()` nativo para un mensaje de simulación

### resources/views/clean/index.blade.php, configuracion/index.blade.php, migration/index.blade.php
index.blade.php:12 (las tres) - `transition: all 0.3s ease` → listar propiedades; sin `prefers-reduced-motion`
clean/index.blade.php:184, migration/index.blade.php:91 - confirmación destructiva con `onclick="return confirm(...)"`: usar modal accesible con descripción del impacto y botón específico ("Ejecutar migración")

### resources/views/superadmin/{email,database,backup}-configs/{create,edit}.blade.php
email-configs/create.blade.php:97,113 (+edit 98,115) - campos de usuario/contraseña de SMTP sin `autocomplete="off"`/`new-password` (disparan el gestor de contraseñas del navegador con credenciales de otro sistema)
database-configs/create.blade.php:93 (+edit 94), backup-configs/create.blade.php:122 (+edit 123) - ídem

### resources/views/empresa/form.blade.php
form.blade.php:107 - `type="tel"` sin `autocomplete="tel"`/`inputmode="tel"`
form.blade.php:117 - `type="email"` sin `autocomplete`/`spellcheck="false"`

### resources/views/ciudade/show.blade.php, cliente/index.blade.php, departamento/show.blade.php (+otros 10)
ciudade/show.blade.php:37,41 - fecha con `->format('d/m/Y H:i')` fijo → formato localizado (`translatedFormat` / `Intl.DateTimeFormat`)
cliente/index.blade.php:78 - ídem
departamento/show.blade.php:47,52 - ídem
document-requirements/show.blade.php:95, configuracion/index.blade.php:85 - ídem

### resources/views/mascota/form.blade.php
mascota/form.blade.php:228 - `<img>` del avatar sin `width`/`height` ni `loading`

### Textos con `...` en lugar de `…`
cliente/arbol-genealogico.blade.php:48, superadmin/oauth-providers/index.blade.php:222,251, visual-simulator.blade.php:171 ("Cargando...") → "Cargando…" (4 ocurrencias)

## Hallazgos transversales (todo `resources/views`)
- **Foco:** `focus-visible` propio: 0 coincidencias; `outline: none` en 3 pantallas de auth. Sin skip link ni `<main>` en ningún layout.
- **Formularios:** ningún `<input>` de auth lleva `autocomplete` (10 campos); 91 `placeholder` sin `…` y sin patrón de ejemplo; sin aviso de cambios sin guardar (`beforeunload`: 0); solo 2 vistas deshabilitan el submit durante la petición.
- **Botones:** 39 `onclick=` en línea (varios navegan o abren acciones); 2 `<div onclick>`; 4 vistas con `href="#"` en navegación (≥12 ocurrencias).
- **Animación:** 0 reglas `prefers-reduced-motion`; 6 `transition: all`.
- **Tipografía/i18n:** 0 `tabular-nums` en tablas numéricas; 0 `translate="no"` para marcas; fechas con formato fijo en ≥12 vistas; botones genéricos ("Enviar", "OK").
- **Imágenes:** 38 `<img>` sin `loading="lazy"`; ≥5 sin `width`/`height`; rutas `public/storage/...` rotas (ver informe en vivo).
- **Listas grandes:** 15 vistas con DataTables en cliente (paginan); sin virtualización necesaria hoy.
- **Tema oscuro:** `data-theme` en lugar de `data-bs-theme`; sin `color-scheme` ni `theme-color`.
- **Zoom:** sin `user-scalable=no` ni `maximum-scale` ✓.
- **Pegado:** sin `onpaste` bloqueado ✓.
- **Confirmación de acciones destructivas:** existe en 6 vistas (`confirm`) y SweetAlert2 en 14 ✓ (mejorable: ver `clean`/`migration`).

## Prioridad
1. **Auth**: layout compartido + `autocomplete`, foco visible, nombre accesible del botón de contraseña, errores inline con `aria-live`, estilo y feedback en forgot-password (todo el bloque `auth/*`).
2. **Layout**: `<main>`, skip link, `aria-label` en controles solo-ícono de navbar, reemplazar `href="#"` por `<button>`/enlaces reales, corregir rutas de imágenes.
3. **Foco y movimiento**: `:focus-visible` global, `prefers-reduced-motion`, quitar `transition: all`.
4. **Formularios de configuración** (SMTP/BD/backup): `autocomplete` correcto para no mezclar credenciales con el gestor del navegador.
5. **Contenido**: fechas localizadas, `…`, etiquetas de botones específicas.

## Verificación pendiente
- Pasada con teclado y lector de pantalla; Lighthouse/axe (no ejecutados).
- Revisar las vistas no abiertas una por una (lista marcada "+N más").

## Notas sobre el repositorio
- La instalación creó `.claude/skills/web-design-guidelines/`, `.agents/skills/…` y `skills-lock.json` en el worktree; **no se versionaron** en esta rama. Decide si quieres commitear la skill al repo (la guía se descarga desde GitHub en cada uso).
