---
agente: codex
estado: terminado
rama: ia/codex/aplica-para-raza
archivos: [app/Models/DocumentRequirement.php, tests/]
---

# U-13: aplicaParaRaza no filtra razas peligrosas

Ver docs/auditorias/informe-unificado-y-plan.md (U-13, C-006).

## Qué hacer
1. Investiga primero qué hace hoy aplicaParaRaza en
   app/Models/DocumentRequirement.php: busca dónde se define, dónde se
   usa, y qué se esperaba que hiciera (revisa el nombre del campo, vistas
   relacionadas, cualquier comentario o lógica cercana).
2. Pruebas primero (commit que falle) que describan el comportamiento
   esperado: un DocumentRequirement con aplicaParaRaza configurado para
   razas específicas solo debería aplicar a esas razas, no a todas.
3. Si tras investigar concluyes que no hay suficiente contexto de negocio
   para saber qué "razas peligrosas" debería filtrar (por ejemplo, no
   existe un catálogo de razas marcadas como peligrosas en el sistema),
   NO inventes la regla de negocio: documenta el hallazgo con precisión
   y dialoga en el Handoff qué decisión de producto falta, en vez de
   implementar algo arbitrario.
4. Si el campo simplemente nunca se conecta a ninguna consulta real (es
   un flag que se guarda pero no se usa en ningún filtro), implementa el
   filtro básico: que las consultas de requisitos documentales respeten
   ese campo.

## Entregable
docs/auditorias/seg037-aplicaparaRaza.md: qué hace hoy, qué decisión de
producto falta (si aplica), y la corrección si fue posible implementarla
con la información disponible.

## Restricciones
- No toques .env ni dependencias.
- Si implementas algo, las pruebas (196 + nuevas) deben pasar.
- Al terminar, vuelve con git switch --detach origin/main.

## Handoff
- Agente y fecha: Codex, 2026-10-03.
- Qué se hizo: se trazó el uso de `aplicaParaRaza`, su esquema, interfaz,
  datos auxiliares e historial. Se confirmó que el flag se consulta, pero no
  existe ninguna clasificación ni relación que permita discriminar razas. El
  hallazgo y las alternativas se documentaron sin inventar una regla.
- Archivos modificados: `docs/auditorias/seg037-aplicaparaRaza.md`.
- Cómo probarlo: `php artisan test` y `composer validate` (ambos ejecutados).
- Pendientes y riesgos: el flag continúa sin efecto hasta definir y persistir
  la clasificación. No se agregaron pruebas porque el modelo actual no permite
  configurar la premisa "razas específicas" que esas pruebas necesitarían.
- Preguntas para el humano: ¿producto prefiere una marca global
  `razas.es_peligrosa` o una relación muchos-a-muchos que seleccione razas por
  requisito? ¿Un requisito específico debe excluir mascotas sin raza?
