# Auditoría de diseño y frontend — web-quality-audit

Fecha: 2026-10-03 · Agente: Claude · Rama: `ia/claude/auditoria-frontend`

## Skill usada y cómo
Skill elegida: **`web-quality-audit` (Addy Osmani)**, la más completa de la lista (Performance + Accesibilidad + SEO + Best Practices, basada en Lighthouse/Core Web Vitals/WCAG).
- No estaba instalada ni disponible en el catálogo de skills de la sesión (`ListSkills`/`SearchSkills` sin resultados). Instalarla con `npx skills add` implica descargar y ejecutar código de terceros y de todos modos no se cargaría en la sesión en curso, así que **leí su `SKILL.md` público** ([addyosmani/web-quality-skills](https://github.com/addyosmani/web-quality-skills)) y apliqué su procedimiento: objetivo → **baseline medido en vivo** → localizar en código → categorizar por severidad y confianza.
- **Medición real:** app local (`php artisan serve`, SQLite local) en el navegador integrado; Performance API (TTFB, DCL, load, LCP, CLS, recursos), `curl` de cabeceras, comprobación de DOM/consola y cálculo de contraste. Para el panel creé un usuario Superadmin de prueba en la copia local de SQLite (credenciales generadas, ya eliminado). Servidor detenido al terminar.
- **No se ejecutó Lighthouse/axe** (no están instalados; no quise descargarlos). Los checks de a11y son manuales/propios y están marcados; falta una pasada con axe y lector de pantalla.
- Se mantiene la regla de solo lectura: no se tocó código de la aplicación ni `.env`.

## Alcance medido
`/login` (desktop claro y oscuro, móvil 375×812), `/forgot-password` (móvil), `/superadmin/dashboard` (panel) y `/razas` (listado con DataTables). Resto de vistas: análisis estático (149 vistas).

## Evidencia
| Señal | Alcance | Resultado | Fuente |
|---|---|---|---|
| TTFB / DCL / load | `/login` | 31 ms / 307 ms / 312 ms (3 req, 63 KB) | Navigation Timing |
| TTFB / DCL / load | `/superadmin/dashboard` | 261 ms / 547 ms / 840 ms (12 req, 242 KB) | Navigation Timing |
| TTFB / DCL / load | `/razas` | — / 639 ms / 686 ms (**28 req, 685 KB**, 6 orígenes) | Navigation Timing |
| CLS | login / dashboard / razas | 0 / **0.032** / 0 (umbral 0.1: pasa) | PerformanceObserver |
| LCP | todas | no reportado (sin elemento candidato válido en el entorno) | PerformanceObserver |
| Long tasks | dashboard | ninguna | PerformanceObserver |
| Imágenes rotas (404) | login, dashboard, razas | login 1/1, dashboard 3/3, razas 2/2 | DOM + `curl` |
| `/public/storage/img/logo.jpg` vs `/storage/img/logo.jpg` | servidor | **404** vs **200** | `curl` |
| Errores de consola | login→dashboard | 404s de recursos, 4× `console.log` de depuración del login, warning "Vite manifest no encontrado" | consola del navegador |
| Cabeceras de seguridad | `/login` | sin CSP, X-Frame-Options, X-Content-Type-Options, Referrer-Policy ni Permissions-Policy; `X-Powered-By: PHP/8.3.33` expuesto | `curl -I` |
| Contraste enlaces (tema oscuro automático de Bootstrap) | login | enlaces `#0d6efd` sobre `#212529` = **3.43:1** (AA exige 4.5:1) | cálculo WCAG |
| Contraste botón 👁️ (oscuro) | login | 3.29:1 | cálculo WCAG |
| Landmark `<main>` / skip link | login, dashboard, razas | **ausentes** | DOM |
| `<h1>` por página | dashboard | **2 `<h1>`** + `<h3>` (sin `<h2>`) | DOM |
| Enlaces sin nombre accesible | navbar del panel | **2** (menú lateral y notificaciones) | DOM |
| `scope` en `<th>` | `/razas` | 0 de 6 | DOM |
| Overflow horizontal móvil | login 375 px | ninguno | DOM |
| `autocomplete` | login | ausente en correo y contraseña | DOM |
| Estilos | `/forgot-password` | **sin ninguna hoja de estilos** (HTML por defecto del navegador) | captura + DOM |
| Feedback de errores | `/forgot-password` (correo inexistente) | **0 mensajes** de error/estado tras enviar | `fetch` del formulario |
| Documentos HTML completos fuera del layout | estático | 12 | grep |
| CDN sin SRI | estático | 0 con `integrity` (~246 referencias) | grep |
| `style=""` / `onclick=` / `console.log` / `alert()`·`confirm()` | estático | 239 / 39 / 15 / 21 | grep |

## Críticos (0)
Ninguno de seguridad de aplicación en el frontend (los de backend ya se trataron en SEG-040/042).

## Alta prioridad (7)

**H-01 · `forgot-password` sin estilos ni feedback.** *(medido)* La página no carga CSS (18 líneas, HTML crudo) y, al enviar un correo, no muestra ningún mensaje de éxito ni de error. Incumple WCAG 3.3.1/4.1.3 y es la primera pantalla que ve quien no puede entrar. Archivo: `resources/views/auth/forgot-password.blade.php`. *Fix:* usar el mismo diseño que login + `@if(session('status'))` y `@error('email')` con `role="alert"`.

**H-02 · Logos y avatares rotos en todas las pantallas.** *(medido)* Las vistas usan `asset('public/storage/img/...')`, que genera `/public/storage/...` → **404**; la URL correcta `/storage/img/logo.jpg` responde 200. Afecta login (`auth/login.blade.php:17`), sidebar (`layouts/sidebar.blade.php:5`), navbar (`layouts/navbar.blade.php:50-69`), `cliente/dashboard.blade.php:14-29`, `mascota/show.blade.php:21`. Se ve el texto alternativo en lugar de la marca. *Fix:* `asset('storage/img/...')`.

**H-03 · Pantallas de auth fuera del layout y desalineadas.** 5 pantallas son HTML completo con su propio `<head>`; login usa Bootstrap 5.3.0 (layout: 5.3.2), forgot-password ninguno (H-01). Mantenimiento ×5 y apariencia inconsistente. *Fix:* `layouts/guest.blade.php` compartido.

**H-04 · Login con depuración en producción.** *(medido en consola)* Cada carga imprime `Login Form: ...` y al enviar registra el correo escrito y la longitud de la contraseña (15 `console.log` en el proyecto). *Fix:* eliminarlos.

**H-05 · Botón de contraseña sin nombre accesible.** `auth/login.blade.php`: `<button onclick="togglePassword()">👁️</button>`, sin `aria-label` ni `aria-pressed` (WCAG 4.1.2). En tema oscuro su contraste es 3.29:1.

**H-06 · Navegación sin semántica y controles sin nombre.** *(medido)* Sin `<main>`, sin skip link (WCAG 2.4.1, 1.3.1); 2 enlaces de la navbar sin nombre (`data-widget="pushmenu"` y campana de notificaciones); 2 `<h1>` en el dashboard. Además la navbar mezcla `data-widget` (AdminLTE 3 / Bootstrap 4 + jQuery) con `data-bs-toggle` (Bootstrap 5), mezcla de generaciones que no está soportada *(verificar comportamiento de dropdown en navegador)*.

**H-07 · Contraste insuficiente en tema oscuro.** *(medido)* Con `prefers-color-scheme: dark` Bootstrap oscurece el login pero los enlaces mantienen `#0d6efd` (3.43:1 < 4.5:1). El resto de pantallas del panel no se probó en oscuro *(verificar)*. *Fix:* definir colores accesibles por tema o desactivar el tema oscuro automático.

## Prioridad media (8)

**M-01 · Peso y terceros en listados.** *(medido)* `/razas`: 28 peticiones, 685 KB, 6 orígenes externos (cdnjs, Google Fonts, jsdelivr, datatables.net, code.jquery.com) y 14 vistas re-cargan DataTables+Buttons+pdfmake+jszip. Aún así load < 1 s en local; en red real será notablemente más lento. *Fix:* bundle con Vite (instalado, pero hoy hay un warning "Vite manifest no encontrado") y cargar pdfmake/jszip solo al exportar.

**M-02 · CDN sin SRI/`crossorigin`.** ~246 referencias sin `integrity`; un CDN comprometido ejecuta código en el panel de Superadmin (relevante por los módulos de BD/migraciones/seeders que expone).

**M-03 · Sin cabeceras de seguridad HTTP.** *(medido)* No hay CSP, X-Frame-Options (clickjacking), X-Content-Type-Options ni Referrer-Policy; `X-Powered-By` expone la versión de PHP. La CSP además choca con 239 `style=""`, 39 `onclick` y scripts en línea (M-05). *Fix:* middleware de cabeceras; deshabilitar `expose_php`.

**M-04 · Dos sistemas de estilos.** Tailwind/Vite declarados (`resources/css/app.css`, `package.json`) y Bootstrap+AdminLTE por CDN en uso; `public/css/app.css` además. Decidir una base.

**M-05 · Estilo y comportamiento en línea.** 239 `style=""`, 39 `onclick=`, bloques `<style>` en 8+ vistas. Impide CSP y theming.

**M-06 · Tablas sin semántica.** *(medido en `/razas`; 34 en el proyecto)* 0 `scope`, sin `<caption>`; acciones solo con ícono.

**M-07 · Formularios.** `autocomplete` ausente (WCAG 1.3.5); errores sin `aria-describedby`/`aria-invalid`; 91 `placeholder` que deben revisarse como posibles sustitutos de `<label>`.

**M-08 · Defecto visible de contenido.** *(medido, captura del dashboard)* El mensaje de bienvenida muestra literalmente `\r\n\r\n` entre párrafos (secuencias de escape sin interpretar en el dato o en su render). Revisar el seeder/entrada de `mensaje-de-bienvenida`.

## Prioridad baja (6)
- **L-01** Idioma mezclado: `__('Create'|'Update'|'Show'|'Back'|'Edit'|'Submit')` (~33 usos) y "created successfully" de los generadores CRUD.
- **L-02** `alert()`/`confirm()` nativos (21) junto a SweetAlert2 (14 vistas).
- **L-03** Sin `<meta name="description">`, canonical ni títulos únicos descriptivos en auth (`<title>` "Iniciar Sesión"); relevante solo para la página pública (login). `robots.txt` permite todo (`Disallow:` vacío) aunque es un panel privado: conviene `Disallow: /` salvo la landing.
- **L-04** Pie con `<a href="#">`, versión fija "1.0.0"; un `target="_blank"` sin `rel="noopener"`.
- **L-05** Foco visible y teclado: no se pudo confirmar estilo `:focus-visible` propio (la comprobación por script no es fiable) *(verificar con teclado)*.
- **L-06** Imágenes sin `width`/`height` (CLS bajo hoy, 0.032, pero con riesgo si cargan las reales).

## Qué está bien
`lang="es"` correcto, viewport sin bloquear zoom, sin overflow horizontal a 375 px, CLS < 0.1, sin long tasks, tablas en `.table-responsive`, DataTables en español, CSRF presente, fuentes con `preconnect` + `display=swap`, sin IDs duplicados en el dashboard, TTFB bajo.

## Resumen por categoría
- **Performance:** CWV medidos aceptables en local (CLS 0.032; LCP no medible); peso/terceros en listados (M-01, M-04). 2 hallazgos.
- **Accesibilidad:** automatizada **no ejecutada** (sin axe/Lighthouse); manuales/medidos: 8 hallazgos (H-01, H-03, H-05, H-06, H-07, M-06, M-07, L-05).
- **SEO:** 1 hallazgo menor (L-03); no aplica a un panel privado salvo la landing/login.
- **Best Practices:** 6 hallazgos (H-02, H-04, M-02, M-03, M-05, L-04); errores 404 en consola y warning de Vite.
- **Agentic Browsing:** no evaluado (no hay WebMCP/`llms.txt`); la semántica pobre (H-06, M-06, M-07) también limita a agentes.

## Prioridad recomendada
1. **H-02 + H-04 + H-01** (rápidos, alto impacto): corregir rutas `asset('storage/...')`, quitar `console.log`, dar estilo y feedback a forgot-password. Riesgo bajo.
2. **H-03 + H-05 + M-07** layout de invitado y auth accesibles (una tarea acotada a `resources/views/auth/*`).
3. **M-03** cabeceras de seguridad (middleware), después **M-02/M-05** para poder activar CSP.
4. **H-06 + M-06** semántica del layout (`<main>`, skip link, nombres) y tablas.
5. **Decisión humana**: base de UI (AdminLTE 3 + Bootstrap 5 vs una sola; Tailwind sí/no) antes de M-01/M-04.
6. **M-08** corregir el dato de bienvenida.

## Verificación pendiente
- Ejecutar Lighthouse y axe sobre `/login`, `/register`, `/forgot-password`, el dashboard y un listado, en móvil y escritorio, y comparar contra la tabla de evidencia.
- Pasada manual con teclado y lector de pantalla (foco, dropdowns, skip link).
- Medir con red real (los números de este informe son locales, sin latencia de CDN).
- Re-ejecutar este baseline tras cada corrección.

## Preguntas para el humano
- ¿Autorizas tareas para los puntos 1–4 de la prioridad recomendada? (los 1 y 2 son de bajo riesgo).
- ¿AdminLTE 3 + Bootstrap 5 es decisión consciente? ¿Se mantiene Tailwind?
- ¿Quieres instalar Lighthouse/axe (descarga de paquetes de terceros) para la medición completa?
