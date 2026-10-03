---
agente: cursor
estado: pendiente
rama:
archivos: [routes/web.php]
---

# Mover auth del constructor a la ruta (3 recursos)

Ver docs/auditorias/seg039-rutas-sin-auth.md. departamentos, empresas y
vacunas_certificaciones tienen auth solo en el constructor del
controlador, no en la ruta. Es mas debil: cualquier middleware que corra
antes de llegar al controlador (como el de modulos) no esta protegido.

## Qué hacer
1. Pruebas primero (commit que falle) confirmando el comportamiento
   actual del middleware de modulo sin auth previo para estos 3 grupos.
2. Mueve auth (y verified si corresponde) a nivel de ruta para
   departamentos, empresas y vacunas_certificaciones, igual que se hizo
   con ciudades en la 034b.
3. Puedes dejar el auth del constructor tambien como capa adicional, o
   quitarlo si resulta redundante: documenta cual eliges y por que.
4. Ejecuta la suite completa: 196 + las nuevas deben pasar.

## Entregable
docs/auditorias/seg041-auth-constructor-a-ruta.md.

## Restricciones
- No toques paths-documentos, tipos-empresas, tipo-documentos, razas,
  barrios, sectores ni mensaje-de-bienvenidas (son la tarea 040, en
  paralelo, para no pisarse).
- No toques .env ni dependencias.
- Al terminar, vuelve con git switch --detach origin/main.
