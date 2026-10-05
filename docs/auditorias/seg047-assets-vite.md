# SEG-047 — Autoalojar assets del CDN con Vite

Fecha: 2026-10-04  
Rama: `ia/cursor/frontend-fase2a-assets-vite`  
Alcance: FE-01 / FE-16 / FE-22 (sin migrar a AdminLTE 4).

## Dependencias (versiones exactas = tags CDN previos)

| Paquete npm | Versión | Antes (CDN) |
|---|---|---|
| `bootstrap` | **5.3.2** | `cdn.jsdelivr.net/npm/bootstrap@5.3.2` |
| `@fortawesome/fontawesome-free` | **6.4.2** | `cdnjs…/font-awesome/6.4.2/css/all.min.css` |
| `sweetalert2` | **11.7.32** | `cdn.jsdelivr.net/npm/sweetalert2@11.7.32` |
| `admin-lte` | **3.2.0** | `cdn.jsdelivr.net/npm/admin-lte@3.2` |
| `jquery` | **3.7.1** | `code.jquery.com/jquery-3.7.1.min.js` |

Notas de instalación:
- `admin-lte@3.2.0` se instaló con `--ignore-scripts` (el `postinstall`/husky de dependencias transitivas falla en Windows).
- Font Awesome: **paquete completo** (`all.min.css` + webfonts). Tree-shake por icono no es sencillo con el build CSS free usado en las vistas AdminLTE; documentado aquí a propósito.

## Entradas Vite

`vite.config.js` declara:
- `resources/css/app.css` + `resources/js/app.js` (Tailwind/welcome; sin cambios de comportamiento)
- `resources/css/admin.css` + `resources/js/admin.js` (**nuevo stack AdminLTE**)

`layouts/app.blade.php` carga solo:
```blade
@vite(['resources/css/admin.css', 'resources/js/admin.js'])
```
Se retiraron los `<link>`/`<script>` de CDN. Google Fonts (Source Sans Pro) se mantiene por CDN con `preconnect` (no formaba parte del listado FE-01 de UI kits).

`admin.js` expone `window.$` / `window.jQuery` / `window.Swal` y reinyecta los scripts de `@stack('scripts')` / `@yield('js')` desde un `<template id="deferred-page-scripts">` para que el jQuery de las vistas hijas no se ejecute antes del módulo ES de Vite.

## Duplicados `public/js` y `public/css`

Tras `grep` en `resources/views` (0 referencias `asset('js/…')` / `asset('css/…')`) y retirar el fallback del layout, se movieron a `_borrar/public-js-css-047/` (no borrado duro):
- `public/js/app.js`, `public/js/bootstrap.js`
- `public/css/app.css` (tenía `--text-color: #0000` en tema oscuro)

## Mediciones Lighthouse (lab, mobile, Lighthouse 13.5)

Entorno: `php artisan serve` en `127.0.0.1:8000`.  
CrUX/campo: no aplica (localhost).

### Antes (CDN en `layouts/app`)

| URL | Perf | LCP (ms) | Transfer (bytes) | Requests |
|---|---:|---:|---:|---:|
| `/login` | 0.98 | 1965 | 79 158 | 5 |
| `/superadmin/dashboard` | 0.70 | 4987 | 516 250 | 18 |

`/login` **no** usa `layouts.app` (sigue con Bootstrap 5.3.0 propio); se midió como control.

### Después (Vite)

No se pudo repetir el lab autenticado en este worktree: no hay `.env` local (AGENTS prohíbe crearlo/copiarlo) y `php artisan optimize:clear` invalidó un `bootstrap/cache/config.php` previo que era lo único que permitía servir la app. Restaurar `.env` en el worktree Cursor queda como pregunta al humano.

Verificación objetiva post-cambio (sí ejecutada):
- `npm run build` OK → `public/build/manifest.json` + `admin-*.css` / `admin-*.js`
- `tests/Feature/AdminAssetsViteTest.php` (verde): dashboard 200, HTML **sin** `cdn.jsdelivr.net` / `cdnjs` / `code.jquery.com`, **con** `/build/assets/` y chunks `admin-`

Tamaños de build (referencia de transferencia potencial, gzip del build):

| Asset | Raw | Gzip |
|---|---:|---:|
| `admin-*.css` | ~1 618 KiB | ~172 KiB |
| `admin-*.js` | ~288 KiB | ~86 KiB |
| webfonts FA (woff2/ttf) | ~900 KiB combined | (según negociación) |

Expectativa: el dashboard deja de abrir 4 orígenes CDN; el peso pasa a same-origin `/build/assets/*`. El CSS Admin+FA sin tree-shake es grande; una fase posterior puede recortar iconos.

## Despliegue Hostinger (`public/build` en `.gitignore`)

Opciones (sin decidir ni cambiar `.gitignore`):

1. **Compilar en CI/local y subir `public/build`** en el deploy FTP/SSH (artefacto no versionado).
2. **Commitear `public/build`** (quitar de `.gitignore`) — simple en hosting sin Node; ensucia el repo y genera diffs grandes.
3. **Build en el servidor** si el plan Hostinger tiene Node (`npm ci && npm run build` post-deploy).

Pregunta para el humano: ¿cuál de las tres se adopta?

## Cómo probar en local

```bash
npm ci --ignore-scripts
npm run build
php artisan test --filter=AdminAssetsViteTest
# Con .env válido:
php artisan serve
# Abrir /superadmin/dashboard y comprobar Network → solo /build/assets/*
```

## Apariencia

No se migró AdminLTE 4 ni se cambiaron versiones de UI. Markup de vistas hijas intacto. Si algo se ve distinto tras restaurar `.env`, reportarlo antes de merge.
