---
agente: claude
estado: pendiente
rama:
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
