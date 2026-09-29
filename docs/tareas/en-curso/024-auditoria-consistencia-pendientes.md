---
agente: cursor
estado: en-curso
rama: ia/cursor/auditoria-consistencia-pendientes
archivos: [docs/auditorias/]
---

# AuditorÃ­a de consistencia y pendientes aplazados (SOLO LECTURA)

Revisa el estado del proyecto en aspectos que NO son de seguridad directa,
y evalÃºa quÃ© sigue pendiente de lo aplazado explÃ­citamente.

## QuÃ© revisar
1. Pendientes aplazados: lee docs/auditorias/pendiente-rotacion-credenciales.md
   y la secciÃ³n de TokenSeeder en seg018-tokenseeder-investigacion.md.
   Confirma que siguen congelados como se documentÃ³ (no los toques) y anota
   si algo cambiÃ³ el contexto de esa decisiÃ³n.
2. Modelos duplicados (Ciudad/Ciudade, Sector/Sectore, TipoEmpresa/TiposEmpresa,
   ver seg021-kernel-y-modelos-duplicados.md): sigue sin consolidar. EvalÃºa
   el esfuerzo y riesgo de consolidarlos ahora.
3. Consistencia general del cÃ³digo: nombres de tabla vs modelo (busca otros
   casos como el de Paseador, ya corregido), convenciones de nombres
   inconsistentes entre inglÃ©s/espaÃ±ol, cÃ³digo muerto o comentado, TODOs
   olvidados en el cÃ³digo.
4. Cobertura de pruebas: quÃ© Ã¡reas de la aplicaciÃ³n (rutas, controladores)
   siguen sin ninguna prueba automatizada. Lista concreta, no estimaciÃ³n.
5. Deuda de infraestructura: no hay integraciÃ³n continua (CI) que ejecute
   `php artisan test` automÃ¡ticamente en cada push. EvalÃºa quÃ© implicarÃ­a
   agregar un workflow de GitHub Actions simple para esto.
6. Login social (Socialite) y 2FA (Fortify): confirma que siguen sin
   pruebas automatizadas (ver fase3-laravel13.md) y lista quÃ© escenarios
   necesitarÃ­an cubrirse.

## Entregable
`docs/auditorias/seg024-consistencia-pendientes.md`: para cada punto,
hallazgos concretos con evidencia (archivo:lÃ­nea donde aplique) y una
recomendaciÃ³n priorizada (alta/media/baja).

## Restricciones
- SOLO LECTURA: no modifiques cÃ³digo, configuraciÃ³n ni dependencias.
- No toques TokenSeeder ni nada relacionado a credenciales.
- Al terminar, vuelve con `git switch --detach origin/main`.
