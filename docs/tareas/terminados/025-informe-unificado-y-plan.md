---
agente: claude
estado: terminada
rama: ia/claude/informe-unificado-plan
archivos: [docs/auditorias/]
---

# Informe unificado de las auditorías 023 y 024, y propuesta de plan de trabajo

## Contexto
Codex (seg023-seguridad-post-l13.md) y Cursor (seg024-consistencia-pendientes.md)
auditaron el proyecto de forma independiente tras la migración a Laravel 13.
Esta tarea NO ejecuta nada: analiza, unifica y PROPONE. El humano decide
después qué se convierte en tarea real.

## Qué hacer
1. Lee ambos informes completos, más los informes previos que referencien
   (seg001 a seg021, fase3-laravel13.md) para tener contexto de qué ya se
   corrigió.
2. Produce un informe unificado que identifique:
   - Hallazgos que APARECEN EN AMBOS informes (coincidencias): mayor
     confianza en que son reales.
   - Hallazgos que solo reportó UNO de los dos: evalúa si el otro lo pasó
     por alto o si no aplica, y di por qué.
   - Contradicciones directas entre ambos informes, si las hay.
3. Para cada hallazgo unificado, clasifica: severidad, esfuerzo estimado
   (bajo/medio/alto), y si depende de otro hallazgo (para ordenar el plan).
4. Propón un plan de trabajo ordenado en tareas discretas, cada una con:
   número propuesto, agente sugerido (claude/codex/cursor), archivos que
   tocaría, y qué otras tareas del plan deben terminar antes (dependencias).
5. Al repartir, ten en cuenta que Cursor NO tiene el mismo protocolo de
   verificación automatizada (scripts/verificar-rama.sh) probado que Claude
   y Codex: sugiere para Cursor tareas de menor riesgo o que sean más
   fáciles de verificar manualmente.
6. Señala explícitamente qué tareas pueden correr en paralelo sin pisarse
   (archivos distintos) y cuáles deben ir en secuencia.

## Entregable
`docs/auditorias/informe-unificado-y-plan.md`:
- Tabla de hallazgos unificados con severidad, esfuerzo, dependencias.
- Plan de tareas propuesto (NO crear los archivos de tarea todavía).
- Una recomendación clara de por dónde empezar.

## Restricciones
- NO crees ningún archivo en docs/tareas/pendientes/ de las tareas que
  propongas: esto es solo un informe y una propuesta para que el humano
  decida. Solo el entregable de esta propia tarea.
- No toques código, dependencias ni `.env`.
- Al terminar, vuelve con `git switch --detach origin/main`.

## Handoff
- Agente y fecha: Claude, 2026-09-28.
- Qué se hizo: se leyeron completos SEG-023, SEG-024 y ~18 informes previos
  (seg001 a seg021, fase1/fase2/fase3, seguridad-predespliegue,
  pendiente-rotacion-credenciales, diagnostico-pruebas,
  auditoria-deps-cursor-local, verificacion-codex,
  pruebas-mascotas-documentos). Antes de escribir conclusiones, se
  verificaron directamente contra el código actual (no solo se repitió lo
  que dicen los informes) los puntos que más pesan en la priorización:
  `config/cache.php`, `CleanController.php`, `bootstrap/providers.php`,
  qué controlador de auth gana en cada ruta de `routes/web.php`,
  `User.php` (trait duplicado y ausencia de `TwoFactorAuthenticatable`),
  ausencia de migración `two_factor_*`, y que `AdminWebToolsTest` no cubre
  AutoClean. Una de esas verificaciones **corrigió** una conclusión de
  SEG-023 (2FA lo trataba como hallazgo de baja severidad/"faltan tests";
  verificado que en realidad no tiene soporte funcional en el modelo/BD,
  se reclasificó como Alta siguiendo a SEG-024). Se identificó además un
  meta-hallazgo no cubierto por ninguna de las dos auditorías nuevas:
  varios hallazgos Críticos/Altos de `seguridad-predespliegue.md`
  (backup a destino arbitrario, reescritura de `.env`, document root)
  nunca se re-verificaron desde que se cerró el acceso vía
  `admin_tools.enabled`.
- Archivos modificados: `docs/auditorias/informe-unificado-y-plan.md`
  (nuevo, el entregable).
- Cómo probarlo: es un informe de solo lectura; no aplica `php artisan
  test` ni `composer validate`. La verificación es la tabla de comandos en
  la sección "0. Qué se leyó y qué se verificó" del entregable, todos
  reproducibles con `grep`/lectura de archivos.
- Pendientes y riesgos: ninguno propio de esta tarea. El entregable señala
  que U-14 (re-verificar SEG-002/003/006/008/009/010) y la decisión de
  stack de auth (propuesta como tarea 030, "decisión humana, no tarea de
  agente") quedan pendientes de que el humano decida seguir adelante.
- Preguntas para el humano: ¿cuál de las 038 tareas propuestas (026-038 en
  el entregable) se aprueban y en qué orden? En particular, la 030 (elegir
  stack de auth oficial: Fortify vs controladores propios) es un
  bloqueador real para 031/032/033 y requiere una decisión de producto que
  este informe no toma por sí mismo.
