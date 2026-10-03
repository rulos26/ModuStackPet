---
agente: claude
estado: pendiente
rama: ia/claude/consolidar-ciudad
archivos: [routes/web.php, app/Http/Controllers/CiudadController.php]
---

# Ajuste a la 034: /ciudades/* sin autenticacion + CRUD roto

Hallazgos de la propia tarea 034, documentados en
docs/auditorias/seg034-consolidar-ciudad.md:

1. URGENTE (seguridad): las rutas /ciudades/* no tienen middleware auth.
   Cualquiera sin sesion puede listar, activar, desactivar y borrar
   ciudades.
2. CRUD roto: /ciudades/create y /ciudades/{id}/edit dan 500. Usan una
   columna id en departamentos, pero la clave primaria es id_departamento.

## Qué hacer
1. Parte seguridad (prioridad): pruebas primero (commit que FALLE)
   confirmando que un invitado sin sesion puede acceder a index, activar,
   desactivar y destroy. Luego aplica auth (y verified si corresponde,
   revisa el patron de otras rutas administrativas similares) al grupo de
   rutas de ciudades. Decide tambien si requiere un rol especifico
   (Superadmin/Admin) revisando quien deberia gestionar ciudades.
2. Parte CRUD: corrige las consultas que usan id en vez de
   id_departamento en CiudadController. Prueba que crear y editar una
   ciudad funcione de principio a fin.
3. Ejecuta la suite completa: 179 + las nuevas deben pasar.

## Entregable
Actualiza docs/auditorias/seg034-consolidar-ciudad.md con ambas
correcciones.

## Restricciones
- No toques Sector/Sectore ni TipoEmpresa/TiposEmpresa (son 035/036,
  despues de esta).
- No toques .env ni dependencias.
- Al terminar, vuelve con git switch --detach origin/main.
