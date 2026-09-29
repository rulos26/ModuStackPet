---
agente: cursor
estado: terminado
rama: ia/cursor/auditoria-consistencia-pendientes
archivos: [docs/auditorias/seg024-consistencia-pendientes.md]
---

# Auditoría de consistencia y pendientes aplazados (SOLO LECTURA)

Revisa el estado del proyecto en aspectos que NO son de seguridad directa,
y evalúa qué sigue pendiente de lo aplazado explícitamente.

## Qué revisar
1. Pendientes aplazados: lee docs/auditorias/pendiente-rotacion-credenciales.md
   y la sección de TokenSeeder en seg018-tokenseeder-investigacion.md.
   Confirma que siguen congelados como se documentó (no los toques) y anota
   si algo cambió el contexto de esa decisión.
2. Modelos duplicados (Ciudad/Ciudade, Sector/Sectore, TipoEmpresa/TiposEmpresa,
   ver seg021-kernel-y-modelos-duplicados.md): sigue sin consolidar. Evalúa
   el esfuerzo y riesgo de consolidarlos ahora.
3. Consistencia general del código: nombres de tabla vs modelo (busca otros
   casos como el de Paseador, ya corregido), convenciones de nombres
   inconsistentes entre inglés/español, código muerto o comentado, TODOs
   olvidados en el código.
4. Cobertura de pruebas: qué áreas de la aplicación (rutas, controladores)
   siguen sin ninguna prueba automatizada. Lista concreta, no estimación.
5. Deuda de infraestructura: no hay integración continua (CI) que ejecute
   `php artisan test` automáticamente en cada push. Evalúa qué implicaría
   agregar un workflow de GitHub Actions simple para esto.
6. Login social (Socialite) y 2FA (Fortify): confirma que siguen sin
   pruebas automatizadas (ver fase3-laravel13.md) y lista qué escenarios
   necesitarían cubrirse.

## Entregable
`docs/auditorias/seg024-consistencia-pendientes.md`: para cada punto,
hallazgos concretos con evidencia (archivo:línea donde aplique) y una
recomendación priorizada (alta/media/baja).

## Restricciones
- SOLO LECTURA: no modifiques código, configuración ni dependencias.
- No toques TokenSeeder ni nada relacionado a credenciales.
- Al terminar, vuelve con `git switch --detach origin/main`.

## Handoff
- Agente y fecha: Cursor, 2026-09-28
- Qué se hizo: auditoría solo lectura de consistencia y pendientes aplazados;
  entregable en `docs/auditorias/seg024-consistencia-pendientes.md` (puntos
  1–6 con IDs C-001..C-012, evidencia y prioridades).
- Archivos modificados:
  - `docs/auditorias/seg024-consistencia-pendientes.md` (nuevo)
  - `docs/tareas/terminados/024-auditoria-consistencia-pendientes.md` (movido
    desde en-curso con Handoff)
- Cómo probarlo: leer el entregable.
- Verificación ejecutada: `php artisan test` → 144 passed (436 assertions); `composer validate --no-check-publish` → valid.
- Pendientes y riesgos:
  - Congelados intactos: rotación de credenciales y TokenSeeder.
  - Hallazgos altos: sin CI; auth Fortify/custom solapado; 2FA en config sin
    trait/columnas; Socialite sin Feature tests; ~25 controladores sin prueba.
  - Consolidación de modelos: esfuerzo medio, riesgo SoftDeletes — no hacer
    “de paso”.
- Preguntas para el humano:
  1. ¿Se aprueba un workflow mínimo de GitHub Actions como próxima tarea?
  2. ¿Implementar 2FA completo o desactivar `Features::twoFactorAuthentication`
     hasta cablearlo?
  3. ¿Unificar auth en Fortify o en controladores custom?
  4. Tras versión 3.0: ¿eliminar TokenSeeder y permisos `tokens.*`?
