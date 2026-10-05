---
agente: cursor
estado: en-curso
rama: ia/cursor/frontend-fase2a-assets-vite
archivos: [package.json, package-lock.json, vite.config.js, resources/js/, resources/css/, resources/views/layouts/app.blade.php]
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
