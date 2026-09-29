---
agente: codex
estado: en-curso
rama: ia/codex/investigar-tokenseeder
archivos: [docs/auditorias/]
---

# Investigar el propósito original de TokenSeeder (solo lectura)

TokenSeeder.php referencia App\Models\AdminDashboard\Token, una clase que no
existe, y no hay tabla `tokens` en las migraciones. Antes de decidir si se
elimina, se quiere saber para qué se pensó usar.

## Qué investigar
1. `git log --all --follow -- database/seeders/TokenSeeder.php`: cuándo se
   creó, quién, en qué commit, y si el mensaje explica su propósito.
2. `git log --all -S "AdminDashboard" --oneline`: si esa clase o carpeta
   existió alguna vez y cuándo se eliminó (o si nunca se creó).
3. Busca en todo el código (rutas, controladores, vistas, config) cualquier
   referencia a "token" que no sea Sanctum/personal_access_tokens, y a
   "AdminDashboard".
4. Revisa si DatabaseSeeder.php lo llama, y en qué orden respecto a otros
   seeders.
5. Busca en docs/ y en los .md de la raíz si hay menciones a un panel o
   dashboard de administración con tokens.

## Entregable
`docs/auditorias/seg018-tokenseeder-investigacion.md`: línea de tiempo de lo
encontrado, hipótesis más probable de para qué se pensó (con la evidencia que
la sostiene) y una recomendación: eliminar, completar la funcionalidad, o dejarlo
documentado como pendiente.

## Restricciones
- SOLO LECTURA: no modifiques ningún archivo de código, solo el entregable.
- Al terminar, vuelve con `git switch --detach origin/main`.
