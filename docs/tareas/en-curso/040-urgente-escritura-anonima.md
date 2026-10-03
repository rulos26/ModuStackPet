---
agente: claude
estado: en-curso
rama: ia/claude/escritura-anonima
archivos: [routes/web.php]
---

# URGENTE: proteger los 7 recursos con escritura anonima (P0)

Ver docs/auditorias/seg039-rutas-sin-auth.md. Siete grupos de rutas
permiten crear/editar/borrar SIN NINGUNA autenticacion:
paths-documentos, tipos-empresas, tipo-documentos, razas, barrios,
sectores, mensaje-de-bienvenidas. paths-documentos ademas expone email y
cedula en lectura anonima: es el mas critico de los siete.

## Qué hacer
1. Pruebas primero (commit que FALLE), para CADA uno de los 7 grupos:
   un invitado sin sesion no puede listar (si expone datos sensibles,
   como paths-documentos), crear, editar ni borrar. Prioriza
   paths-documentos primero y en su propio commit separado si quieres
   aislar la correccion mas critica.
2. Aplica auth (y verified) a nivel de RUTA (no solo en el constructor
   del controlador) para los 7 grupos. Revisa si necesitan ademas un rol
   especifico (Superadmin/Admin), siguiendo el mismo criterio que se usó
   para ciudades en la tarea 034b.
3. Ejecuta la suite completa: 196 + las nuevas deben pasar.

## Entregable
docs/auditorias/seg040-escritura-anonima.md: que se protegio, con que
rol, y confirmacion de que paths-documentos ya no expone datos sin login.

## Restricciones
- No toques departamentos, empresas ni vacunas_certificaciones (son la
  tarea 041, en paralelo, para no pisarse).
- No toques .env ni dependencias.
- Al terminar, vuelve con git switch --detach origin/main.
