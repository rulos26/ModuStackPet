---
agente: cursor
estado: pendiente
rama:
archivos: [docs/auditorias/]
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
