---
agente: codex
estado: terminada
rama: ia/codex/kernel-modelos-duplicados
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

## Handoff
- Agente y fecha: Codex, 2026-09-28.
- Qué se hizo: se eliminó del árbol versionado `app/Http/Kernel.php`, confirmado
  como código muerto bajo la configuración de `bootstrap/app.php`. Se investigó
  cada par de modelos y se determinó que las seis clases tienen consumidores
  activos. Se recomienda consolidar posteriormente hacia `Ciudad`, `Sector` y
  `TipoEmpresa`, con pruebas de caracterización previas por las diferencias en
  `SoftDeletes` y clave primaria.
- Archivos modificados: `app/Http/Kernel.php` (eliminado),
  `docs/auditorias/seg021-kernel-y-modelos-duplicados.md` y este archivo de
  tarea. La copia recuperable local del Kernel quedó en `_borrar/Kernel.php`,
  ignorada por Git.
- Cómo probarlo: `php artisan test` — 144 pruebas y 436 aserciones correctas;
  `composer validate` — `composer.json` válido; `php artisan route:list` para
  ciudades, sectores y tipos de empresa — 23 rutas listadas correctamente.
- Pendientes y riesgos: `vendor` local reporta Laravel 11.56.1, mientras
  `composer.lock` fija 12.69.2; conviene sincronizar dependencias en una tarea
  aparte. La consolidación de modelos no se aplicó por ser investigación de
  solo lectura y puede cambiar borrados físicos actuales por borrados lógicos.
- Preguntas para el humano: ¿se crea una tarea para consolidar los tres pares
  hacia los modelos singulares y añadir pruebas de los CRUD/relaciones?
