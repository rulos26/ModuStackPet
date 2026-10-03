---
agente: codex
estado: pendiente
rama:
archivos: [tests/Feature/]
---

# U-09: pruebas para controladores sin cobertura

Ver docs/auditorias/informe-unificado-y-plan.md (U-09, C-009) y
docs/auditorias/seg024-consistencia-pendientes.md para la lista original
de ~25 controladores sin Feature test.

NOTA: esta tarea se publica junto con la 037 para el mismo agente
(Codex). Completa primero la que prefieras, pero no trabajes en ambas
ramas a la vez sin fusionar una antes de empezar la otra, para evitar
confusion de contexto. Sugerencia: termina 037 primero (tiene alcance
mas acotado), luego toma esta.

## Qué hacer
1. Re-lista los controladores sin Feature test AHORA MISMO (puede haber
   cambiado desde seg024, ya que 034/035/040/041 agregaron cobertura a
   varios). No asumas la lista vieja esta actualizada.
2. Prioriza los que manejan datos de negocio reales sobre los que son
   solo catalogos ya cubiertos indirectamente.
3. Para cada controlador elegido, escribe Feature tests de los caminos
   principales: index, show, create/store con datos validos e invalidos,
   update, destroy, y verificacion de autorizacion (quien puede y quien
   no).
4. NO modifiques codigo de app/. Si una prueba revela un bug, documentalo
   sin corregirlo, igual que se hizo en la tarea 015.
5. No necesitas cubrir los 25 de una vez: prioriza por impacto y entrega
   lo que alcances con buena calidad, documentando que queda pendiente.

## Entregable
docs/auditorias/seg038-pruebas-controladores.md: lista actualizada de
cobertura, que se cubrio en esta tarea, que queda pendiente, bugs
encontrados si los hay.

## Restricciones
- Solo tests/. No toques .env, dependencias ni codigo de app/.
- Al terminar, vuelve con git switch --detach origin/main.
