---
agente: claude
estado: en-curso
rama: ia/claude/consolidar-tipoempresa
archivos: [app/Models/TipoEmpresa.php, app/Models/TiposEmpresa.php, app/Http/Controllers/, resources/views/, tests/]
---

# U-10 (parte 3 de 3): consolidar TipoEmpresa/TiposEmpresa

Ultima de la serie. Ver docs/auditorias/seg034-consolidar-ciudad.md y
seg035-consolidar-sector.md como referencia del mismo proceso, ya
aplicado dos veces con exito.

## Qué hacer
1. Pruebas de caracterización primero del comportamiento actual de ambos
   modelos.
2. Determina cual modelo conservar (revisa uso real).
3. Migra todos los consumidores al modelo que se conserva.
4. Mueve el modelo descartado a _borrar/modelos/.
5. Revisa de inmediato si las rutas de tipos-empresas tienen auth a nivel
   de ruta (ya deberia, se corrigio en la tarea 040 - confirmalo, no lo
   des por hecho).
6. Revisa bugs de columna de clave primaria o nombre de parametro de
   ruta, como en Ciudad y Sector. Corrigelos si los hay.
7. Revisa tambien la regla unique del request: si el modelo usa
   SoftDeletes, debe ignorar los eliminados (como se corrigio hoy en
   sectores).

## Entregable
docs/auditorias/seg036-consolidar-tipoempresa.md.

## Restricciones
- No toques .env ni dependencias.
- Al terminar, vuelve con git switch --detach origin/main.
