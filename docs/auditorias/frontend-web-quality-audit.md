# Auditoría frontend — web-quality-audit (Addy Osmani)

Fecha: 2026-10-03 (código) · Medición lab: 2026-10-04  
Proyecto: ModuStackPet  
Skill elegido: **web-quality-audit** ([addyosmani/web-quality-skills](https://github.com/addyosmani/web-quality-skills))  
Método: inspección de código + **Lighthouse 12.2.1 lab (mobile)** sobre `http://127.0.0.1:8000`.  
Reportes HTML/JSON locales (no versionados): `_borrar/lighthouse/login.report.*`, `_borrar/lighthouse/dashboard.report.*`.

## Alcance

| Superficie | Rutas / archivos | Notas |
|---|---|---|
| Shell autenticado | `resources/views/layouts/app.blade.php`, navbar, sidebars | AdminLTE 3 + Bootstrap 5 + jQuery |
| Auth pública | `resources/views/auth/login.blade.php` (+ register/reset) | Bootstrap 5 standalone |
| Landing | `resources/views/welcome.blade.php` | Plantilla Laravel + Tailwind (casi no usada en app) |
| Dashboard medido | `/superadmin/dashboard` (sesión Superadmin) | Representa el shell AdminLTE pesado |
| Assets | `package.json`, `vite.config.js`, `resources/css/app.css` | Vite/Tailwind presentes pero poco integrados |

### Condiciones de la medición (lab)

| Parámetro | Valor |
|---|---|
| Herramienta | Lighthouse 12.2.1 (`npx`), Chrome headless local |
| Form factor | **mobile** (emulación) |
| Servidor | `php artisan serve` → `127.0.0.1:8000` |
| `/login` | URL pública |
| `/superadmin/dashboard` | Cookie de sesión vía login previo (Puppeteer); no se documentan credenciales |
| Campo (CrUX) | **No disponible** (localhost) |

---

## Audit results

### Evidence (medido)

| Signal | Scope/conditions | Result | Source |
|--------|------------------|--------|--------|
| Lighthouse Performance | `/login`, mobile | **96** | Lighthouse lab |
| Lighthouse Accessibility | `/login`, mobile | **92** | Lighthouse lab |
| Lighthouse Best Practices | `/login`, mobile | **96** | Lighthouse lab |
| Lighthouse SEO | `/login`, mobile | **92** | Lighthouse lab |
| LCP | `/login`, mobile | **2.3 s** (score 0.94) | Lighthouse |
| FCP | `/login`, mobile | **2.3 s** | Lighthouse |
| TBT | `/login`, mobile | **0 ms** | Lighthouse |
| CLS | `/login`, mobile | **0** | Lighthouse |
| Transfer size | `/login`, mobile | **72 KiB** | Lighthouse |
| Lighthouse Performance | `/superadmin/dashboard`, mobile | **69** | Lighthouse lab |
| Lighthouse Accessibility | `/superadmin/dashboard`, mobile | **80** | Lighthouse lab |
| Lighthouse Best Practices | `/superadmin/dashboard`, mobile | **96** | Lighthouse lab |
| Lighthouse SEO | `/superadmin/dashboard`, mobile | **92** | Lighthouse lab |
| LCP | dashboard, mobile | **5.1 s** (score 0.25) — **falla umbral 2.5 s** | Lighthouse |
| FCP | dashboard, mobile | **5.0 s** (score 0.10) | Lighthouse |
| TBT | dashboard, mobile | **40 ms** | Lighthouse |
| CLS | dashboard, mobile | **0** | Lighthouse |
| TTI / Speed Index | dashboard, mobile | **5.2 s** / **5.0 s** | Lighthouse |
| Transfer size | dashboard, mobile | **504 KiB** | Lighthouse |
| Unused CSS | dashboard | **~145 KiB** (AdminLTE 110 + FA 18 + Bootstrap 16) | Lighthouse |
| Console errors | ambas URLs | **404** `…/public/storage/img/logo.jpg` (+ `default.png` en dashboard) | Lighthouse |
| Contrast fail | `/login` | Links “Olvidaste…” / “Regístrate” vs fondo oscuro | Lighthouse |
| `link-name` fail | dashboard | pushmenu + campana sin nombre accesible | Lighthouse |
| Core Web Vitals (CrUX) | producción | **No disponible** (lab local) | — |

### Critical issues (1 found)

- **[Best practices / UX]** Rutas de assets con `asset('public/...')` → **404 medido** en lab.  
  - **Impact:** branding roto, avatar/logo rotos, falla `errors-in-console` en Lighthouse (Best Practices).  
  - **Evidence (lab):**  
    - `/login` → `http://127.0.0.1:8000/public/storage/img/logo.jpg` **404**  
    - `/superadmin/dashboard` → mismo logo + `…/public/storage/img/default.png` **404**  
  - **Evidence (código):** `auth/login.blade.php`, `layouts/sidebar.blade.php`, `layouts/navbar.blade.php`, etc.  
  - **Fix:** quitar el prefijo `public/` de `asset()`; usar `asset('storage/...')` o `Storage::url()`.

### High priority (7 found)

- **[Performance]** Dashboard autenticado: **LCP 5.1 s / FCP 5.0 s** (Performance **69**). Login ligero: LCP **2.3 s** (Performance **96**).  
  - **Impact:** el shell AdminLTE+CDN falla el umbral “good” de LCP (&lt; 2.5 s) en lab mobile.  
  - **Evidence (lab):** transfer **504 KiB** vs **72 KiB** en login; unused CSS **~145 KiB** (AdminLTE 110 KiB, Font Awesome 18 KiB, Bootstrap 16 KiB).  
  - **Fix:** self-host + tree-shake/subset FA; diferir AdminLTE/SweetAlert si no son above-the-fold; Vite para CSS crítico; re-medir tras el cambio.

- **[Accessibility]** Controles del chrome sin nombre: Lighthouse **`link-name` score 0** en dashboard.  
  - **Impact:** lectores de pantalla no anuncian menú/notificaciones.  
  - **Evidence (lab):** fallan  
    - `<a class="nav-link" data-widget="pushmenu" …>`  
    - `<a class="nav-link" data-bs-toggle="dropdown" …>` (campana)  
  - **Fix:** `aria-label="Abrir menú"` / `aria-label="Notificaciones"`.

- **[Accessibility]** Contraste insuficiente medido.  
  - **Impact:** WCAG AA; Accessibility login **92**, dashboard **80**.  
  - **Evidence (lab):**  
    - Login: links “¿Olvidaste…?” / “¿Regístrate?” sobre fondo oscuro.  
    - Dashboard: nombre “root” en navbar, título “Acciones Rápidas”, footer.  
  - **Fix:** subir contraste de links (p. ej. `#8ab4ff` o underline + color más claro) y revisar footer AdminLTE.

- **[Accessibility]** Más fallos a11y en dashboard (lab): `aria-required-children`, `heading-order`, `listitem`.  
  - **Impact:** árbol ARIA/HTML inválido en sidebar AdminLTE / markup de menú.  
  - **Evidence:** Accessibility score **80** con 5 auditorías binarias en 0.  
  - **Fix:** revisar roles del treeview AdminLTE y jerarquía `h1`→`h3`/`h5` en acciones rápidas.

- **[Accessibility]** Sin “Skip to main content”; contenido en `<section class="content">` sin `<main>` (código; no cubierto por score Lighthouse).  
  - **Fix:** skip-link + `id="main-content"`.

- **[SEO]** Sin meta description (lab: SEO **92**, auditoría `meta-description` = 0 en ambas URLs). Marca inconsistente en UI (“ModuStackPetLTE”, welcome “Laravel”).  
  - **Fix:** `<meta name="description">` en layouts; titles `ModuStackPet | …`.

- **[Design system]** Tailwind (welcome) + AdminLTE/Bootstrap app + Bootstrap 5.3.0 en login (código).  
  - **Fix:** una sola versión Bootstrap + tokens de marca.

### Medium priority (5 found)

- **[Best practices]** Errores de consola medidos (404 de imágenes) — ligado al Critical de `asset('public/...')`. Además quedan `console.log` de depuración en login (código).  
  - **Fix:** arreglar assets; envolver logs en `config('app.debug')`.

- **[Accessibility / UX]** Tema oscuro: se setea `data-theme` en `<html>` pero las clases `.dark-theme` / `.light-theme` de `resources/css/app.css` no se aplican de forma coherente al body AdminLTE.  
  - **Impact:** preferencia del SO no se refleja; contraste impredecible.  
  - **Fix:** o integrar dark mode AdminLTE de verdad, o quitar el script hasta que exista diseño.

- **[Accessibility]** Sidebar Admin comentado (`layouts/sidebar.blade.php:11-13`): rol Admin puede quedar sin navegación lateral.  
  - **Impact:** UX rota para Admin (depende de otras entradas).  
  - **Fix:** reactivar `@include('admin.sidebar')` o redirigir a un hub con enlaces claros.

- **[SEO]** Sin sitemap/robots revisados en esta pasada; app es mayormente autenticada (OK), pero login/register deberían ser indexables con meta correctas si hay captación pública.  
  - **Fix:** meta robots conscientes; OG solo en páginas públicas.

- **[Best practices]** CDN sin Subresource Integrity (SRI) en CSS/JS críticos.  
  - **Impact:** riesgo supply-chain si el CDN se compromete.  
  - **Fix:** añadir `integrity` + `crossorigin`, o self-host.

### Low priority (4 found)

- **[Design]** Uso de emoji en títulos de producto (`cliente/dashboard.blade.php`: “🐾 …”) y controles.  
  - **Impact:** inconsistencia tipográfica y problemas de fuente en algunos SO.  
  - **Fix:** iconos SVG/FA alineados al design system.

- **[Design]** Estilos inline abundantes (bordes negros, radios 15px, anchos fijos) en dashboards frente a tokens AdminLTE.  
  - **Fix:** clases utilitarias o SCSS de marca (`--brand-primary`, etc.).

- **[Performance]** DataTables / scripts por vista en varios `index.blade.php` (patrón repetido) sin code-splitting.  
  - **Fix:** cargar DataTables solo en páginas que lo necesiten vía `@stack('scripts')` + Vite entry.

- **[Agentic browsing]** Superficie semántica irregular (icon-only controls, falta de `<main>`). No hay `llms.txt` (opcional; no requerido).  
  - **Fix:** mismos arreglos a11y mejoran esta categoría.

---

## Summary

### Scores Lighthouse (lab mobile, 2026-10-04)

| URL | Perf | A11y | Best Practices | SEO | LCP | Transfer |
|---|---:|---:|---:|---:|---:|---:|
| `/login` | **96** | **92** | **96** | **92** | 2.3 s | 72 KiB |
| `/superadmin/dashboard` | **69** | **80** | **96** | **92** | **5.1 s** | 504 KiB |

| Categoría | Estado medido | Hallazgos |
|---|---|---|
| Performance | Login OK en lab; **dashboard LCP falla** (5.1 s) | 1 High medido, 1 Low |
| Accessibility | Login 92 / dashboard **80** (contrast, link-name, ARIA, headings) | 4 High |
| SEO | 92 en ambas; falta meta description | 1 High |
| Best Practices | 96; console 404 por assets | 1 Critical medido |
| Design / UX | Confirmado: logo roto, `\r\n` literales en bienvenida, marca LTE | 1 High, 2 Low |
| Agentic Browsing | Señales pobres (`link-name`); sin Lighthouse Agentic category en esta corrida | 1 Low |

### Recommended priority

1. **Corregir `asset('public/...')`** — Critical medido (404 + console errors); re-correr Lighthouse BP.  
2. **`aria-label` en pushmenu/campana + contraste** de links/footer — sube A11y del dashboard desde 80.  
3. **Recortar CSS CDN / AdminLTE** — LCP 5.1 s → objetivo &lt; 2.5 s; hay ~145 KiB CSS sin usar.  
4. **Meta description + marca unificada** (“ModuStackPet”).  
5. **Re-medir** las mismas dos URLs en mobile tras los fixes; opcional: CrUX en staging HTTPS.

### Verification

- [x] Inspección de layouts, auth, package/Vite  
- [x] Lighthouse mobile `/login` (JSON/HTML en `_borrar/lighthouse/`)  
- [x] Lighthouse mobile `/superadmin/dashboard` (sesión autenticada)  
- [ ] Re-run Lighthouse tras fix de assets/a11y  
- [ ] Prueba con teclado / lector de pantalla  
- [ ] CrUX / PageSpeed en URL pública HTTPS  

### Checklist pre-deploy (skill)

- [ ] Core Web Vitals passing — **lab:** login LCP OK; **dashboard LCP no** (5.1 s)  
- [ ] No accessibility errors — **lab:** fallos contrast / link-name / ARIA en dashboard  
- [ ] No console errors — **lab:** 404 de logo/avatar  
- [ ] HTTPS working — depende del despliegue (lab fue HTTP local)  
- [ ] Meta tags present — **faltan** description/OG  

### Observación UX adicional (navegación manual)

En `/superadmin/dashboard` el texto de bienvenida muestra literales `\r\n\r\n` (saltos no convertidos a HTML). No baja el score Lighthouse, pero degrada la percepción de calidad. Corregir en la vista/controlador (`nl2br` / `Str::markdown` / limpiar al guardar).

---

## Nota sobre la elección del skill

De la lista aportada se eligió **web-quality-audit (Addy Osmani)** porque combina Performance, A11y, SEO y Best Practices, y obliga a separar lab medido vs hipótesis de código (ahora con números en `/login` y `/superadmin/dashboard`).
