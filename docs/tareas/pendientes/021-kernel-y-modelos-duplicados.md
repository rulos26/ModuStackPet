---
agente: codex
estado: pendiente
rama:
archivos: [app/Http/Kernel.php, app/Models/, docs/auditorias/]
---

# Eliminar Kernel.php muerto e investigar modelos duplicados

## Parte A: Kernel.php
Confirmado en la tarea 010 que Laravel 11/12 no lo usa (bootstrap/app.php lo
reemplazó). Elimínalo. Ejecuta toda la suite para confirmar que nada dependía
de él por accidente.

## Parte B: modelos duplicados (Ciudad/Ciudade, Sector/Sectore, TipoEmpresa/TiposEmpresa)
Señalado en seg013-modulos-y-paseador.md. SOLO INVESTIGAR, no modificar:
1. Para cada par, busca todos los usos de cada clase (controladores, vistas,
   relaciones Eloquent, factories).
2. Determina si ambas clases del par se usan activamente o si una está muerta.
3. Si alguna migración o dato reciente sugiere cuál es la "correcta".

## Entregable
`docs/auditorias/seg021-kernel-y-modelos-duplicados.md`: confirmación de la
eliminación de Kernel.php, y para cada par de modelos: cuál se usa, cuál no,
y una recomendación (no la apliques).

## Restricciones
- Parte A: solo elimina Kernel.php, nada más.
- Parte B: solo lectura, sin cambios de código.
- Al terminar, vuelve con `git switch --detach origin/main`.
