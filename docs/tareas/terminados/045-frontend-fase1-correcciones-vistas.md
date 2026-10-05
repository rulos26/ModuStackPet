---
agente: claude
estado: terminado
rama: ia/claude/frontend-fase1
archivos: [resources/views/]
---

# Fase 1 parcial del informe unificado de frontend

Ver docs/auditorias/informe-unificado-frontend.md (FE-05, FE-07, FE-08,
FE-13, FE-21). Solo correcciones de bajo riesgo en vistas.

## Qué hacer
1. FE-08: corrige las rutas asset('storage/...') que dan 404 (logos y
   avatares). Verifica con grep cuales y a que ruta real apuntan.
2. FE-13: elimina los console.log del login y de otras vistas.
3. FE-21: normaliza los \r\n mezclados en las vistas afectadas.
4. FE-07: estiliza forgot-password como el resto de auth, agrega
   lang="es" y mensajes en español.
5. FE-05: un unico <h1> por vista (template_title vacio) SOLO en las
   vistas hijas.
6. Pruebas: donde sea testeable (ej. una vista no debe contener
   console.log), agrega una prueba. Suite completa en verde.

## Entregable
docs/auditorias/seg045-frontend-fase1.md con lo corregido y lo que no
se pudo.

## Restricciones
- NO toques layouts/app.blade.php, navbar ni los sidebars: la Fase 2
  los reescribe.
- No toques .env ni dependencias.
- Antes de eliminar o renombrar algo, usa grep para confirmar que nada
  mas lo referencia.
- Al terminar, vuelve con git switch --detach origin/main.

## Handoff
- Agente y fecha: Claude, 2026-10-04
- Qué se hizo: FE-08 (rutas de imagen sin `public/`), FE-13 (console.log fuera de login, empresa/form y document-requirements), FE-21 (`\r\n` literales en los 4 dashboards y mensaje-de-bienvenida), FE-07 (forgot-password estilizado, `lang="es"`, estado/errores), FE-05 (`template_title` y un único `<h1>` en vistas hijas). Detalle en `docs/auditorias/seg045-frontend-fase1.md`.
- Archivos modificados: 26 vistas en `resources/views/` (ninguna de layouts/navbar/sidebar), `tests/Feature/FrontendFase1Test.php`, el informe y el movimiento de la tarea.
- Cómo probarlo: `php artisan test` (324 passed); `php artisan view:cache`; abrir /forgot-password y un dashboard.
- Pendientes y riesgos: navbar y sidebar siguen con `asset('public/…')` (Fase 2); el archivo versionado se llama `desfault.png`, no `default.png` (avatar por defecto sigue 404; propuesta: `git mv`); sin prueba en navegador; las pruebas se escribieron después de los cambios.
- Preguntas para el humano: ¿Renombro `desfault.png` → `default.png` (binarios versionados)? ¿Se limpia el `\r\n` también en el dato/formulario?
