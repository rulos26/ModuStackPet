---
agente: codex
estado: pendiente
rama:
archivos: [app/Models/Raza.php, app/Models/DocumentRequirement.php, database/migrations/, resources/views/, tests/]
---

# U-13: campo de raza con cuidados especiales (bozal, manejo, etc.)

Decision humana (ver docs/auditorias/seg037-aplicaparaRaza.md para la
investigacion previa de Codex, que confirmo que aplicaParaRaza() siempre
retorna true y no existe catalogo de razas peligrosas):

aplicaParaRaza NO es sobre prohibir razas. Es sobre marcar razas que
requieren cuidados especiales al pasear o manejar (ejemplo: bozal
obligatorio), y ese dato debe quedar visible en la hoja de vida de la
mascota, para que paseadores y cuidadores lo sepan.

## Qué hacer
1. Revisa el modelo Raza y confirma si ya existe algun campo para esto
   (busca "peligrosa", "bozal", "cuidado especial" en migraciones y
   modelos). Si no existe, se necesita.
2. Si falta: crea una migracion NUEVA que agregue a la tabla razas un
   campo booleano (ej. requiere_cuidado_especial o similar, usa el
   nombre que mejor encaje con las convenciones del proyecto) y un campo
   de texto para describir el cuidado (ej. "usar bozal", "manejo con
   correa corta").
3. Muestra ese dato en la hoja de vida / ficha de la mascota (vista
   donde se ve la informacion de la mascota), de forma visible, cuando
   la raza lo tenga marcado.
4. Pruebas primero: una mascota de una raza marcada con cuidado especial
   debe mostrar esa advertencia en su ficha; una de raza sin marca no la
   muestra.
5. Semilla de datos: NO inventes qué razas son peligrosas ni listas
   oficiales. Deja el campo en false/vacio por defecto para todas las
   razas existentes - que Superadmin lo configure despues desde el panel
   de razas (ya protegido con auth desde la tarea 040).
6. Revisa aplicaParaRaza(): decide si se conecta a este nuevo campo o si
   se elimina por ser codigo muerto no relacionado. Documenta la
   decision.

## Entregable
docs/auditorias/seg037-raza-cuidados-especiales.md: que se implemento,
captura o descripcion de donde se ve en la ficha de la mascota.

## Restricciones
- No inventes una lista de razas peligrosas. El dato lo carga un humano
  despues, desde el panel ya existente.
- No toques .env ni dependencias.
- Al terminar, vuelve con git switch --detach origin/main.
