---
agente: claude
estado: pendiente
rama:
archivos: [docs/auditorias/]
---

# Unificar las 5 auditorías independientes de frontend

Cinco auditorias independientes de frontend ya estan en docs/auditorias/:
- auditoria-web-design-guidelines.md (Claude, skill Vercel)
- auditoria-frontend-diseno.md (Claude, medicion en vivo)
- auditoria-web-design-guidelines-codex.md (Codex, skill Vercel)
- auditoria-diseno-frontend.md (Codex, segunda pasada)
- auditoria-web-design-guidelines-cursor.md (Cursor, skill Vercel)
- frontend-web-quality-audit.md (Cursor, web-quality-audit + Lighthouse real)

## Qué hacer
1. Lee las 5 (6 archivos) completas.
2. Identifica hallazgos que coinciden entre varias fuentes independientes
   (mayor confianza, van primero por prioridad).
3. Identifica hallazgos unicos de cada una (no se pierden, se incluyen
   igual).
4. HALLAZGO PRIORITARIO CONFIRMADO: resources/views/layouts/app.blade.php
   carga por CDN (sin paquete Composer/npm real) AdminLTE 3.2, Bootstrap
   5.3.2, Font Awesome 6.4.2 y SweetAlert2 11.7.32, simultaneamente con
   Tailwind 3.4.13 compilado localmente. Esto es la causa raiz de varios
   otros hallazgos (peso de pagina, LCP, posible conflicto de estilos).
   Debe quedar como el hallazgo #1 del informe, con su propia seccion.
5. Clasifica cada hallazgo por severidad y por tipo (accesibilidad,
   rendimiento, consistencia visual, deuda tecnica de dependencias).
6. Para cada hallazgo, cita el archivo:linea original y de cual(es)
   auditoria(s) viene.

## Entregable
docs/auditorias/informe-unificado-frontend.md: mismo espiritu que
informe-unificado-y-plan.md (el de seguridad), pero para frontend.
Debe incluir una tabla resumen y una seccion de plan de accion sugerido
en fases (no implementes nada, solo propone el plan).

## Restricciones
- SOLO LECTURA de codigo, no modifiques nada de app/ ni resources/.
- Al terminar, vuelve con git switch --detach origin/main.
