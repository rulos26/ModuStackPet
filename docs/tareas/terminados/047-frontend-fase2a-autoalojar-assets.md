---
agente: cursor
estado: terminado
rama: ia/cursor/frontend-fase2a-assets-vite
archivos: [package.json, package-lock.json, vite.config.js, resources/js/admin.js, resources/css/admin.css, resources/views/layouts/app.blade.php, tests/Feature/AdminAssetsViteTest.php, docs/auditorias/seg047-assets-vite.md]
---

# Fase 2a: autoalojar los assets del CDN con Vite (sin cambiar versiones)

Ver docs/auditorias/informe-unificado-frontend.md (FE-01, FE-16, FE-22).
app.blade.php carga por CDN, sin SRI: Bootstrap 5.3.2, Font Awesome
6.4.2, SweetAlert2 11.7.32, AdminLTE 3.2 y jQuery 3.7.1.

Esta tarea NO migra a AdminLTE 4. Solo mueve lo mismo a dependencias
reales, compiladas con Vite. Mismas versiones, mismo markup, misma
apariencia.

## Qué hacer
1. Declara esas dependencias en package.json con las versiones EXACTAS
   que hoy se cargan por CDN. Verifica en el layout cuales se usan de
   verdad y si hay duplicados en public/js o public/css.
2. Crea las entradas de Vite (CSS y JS) y cargalas con @vite en el
   layout, con ambas entradas. Retira las etiquetas de CDN.
3. Font Awesome: importa solo lo necesario si es sencillo; si no, el
   paquete completo y documentalo.
4. Compara antes y despues con Lighthouse en login y dashboard
   (transferencia, LCP). Registra los numeros.
5. Suite completa en verde y composer validate.
6. DESPLIEGUE: public/build esta en .gitignore y la produccion es
   Hostinger. Investiga y documenta las opciones (compilar local y
   subir, commitear build, o build en el servidor si hay Node). NO
   decidas ni cambies .gitignore: dejalo en el Handoff como pregunta.

## Entregable
docs/auditorias/seg047-assets-vite.md: dependencias, mediciones antes y
despues, opciones de despliegue.

## Restricciones
- No cambies versiones ni migres a AdminLTE 4 en esta tarea.
- No toques otras vistas fuera del layout.
- Antes de borrar archivos de public/, usa grep para confirmar que nada
  los referencia.
- Si algo se ve distinto en el navegador, deten y reportalo.
- Al terminar, vuelve con git switch --detach origin/main.

## Handoff
- Agente y fecha: cursor, 2026-10-04
- Qué se hizo: Bootstrap 5.3.2, Font Awesome 6.4.2 (paquete completo), SweetAlert2 11.7.32, AdminLTE 3.2.0 y jQuery 3.7.1 declarados en package.json; entradas Vite `resources/css/admin.css` + `resources/js/admin.js`; layout sin CDN y con `@vite` de ambas; duplicados `public/js|css` movidos a `_borrar/public-js-css-047/`; Lighthouse baseline CDN registrado; prueba `AdminAssetsViteTest`; entregable `docs/auditorias/seg047-assets-vite.md`.
- Archivos modificados: `package.json`, `package-lock.json`, `vite.config.js`, `resources/css/admin.css`, `resources/js/admin.js`, `resources/views/layouts/app.blade.php`, `tests/Feature/AdminAssetsViteTest.php`, `docs/auditorias/seg047-assets-vite.md`; eliminados del árbol versionado `public/js/*`, `public/css/app.css` (copias en `_borrar/`).
- Cómo probarlo: `npm ci --ignore-scripts && npm run build && php artisan test` (o `--filter=AdminAssetsViteTest`). Con `.env` válido: `php artisan serve` y revisar Network en `/superadmin/dashboard` (solo `/build/assets/*`).
- Pendientes y riesgos: Lighthouse “después” no se pudo repetir en lab (worktree Cursor sin `.env`; AGENTS prohíbe crearlo). `admin.css` compilado es muy grande (~1.6 MB raw / ~172 KB gzip) por FA completo. Scripts de página van en `<template id="deferred-page-scripts">` — si alguna vista inyecta HTML no-script en el stack, revisar. Instalación de `admin-lte` requiere `--ignore-scripts` en Windows.
- Preguntas para el humano: ¿Cómo desplegar `public/build` en Hostinger (subir artefacto / commitear build / Node en servidor)? ¿Restaurar `.env` en el worktree Cursor para re-medir Lighthouse post-Vite?
