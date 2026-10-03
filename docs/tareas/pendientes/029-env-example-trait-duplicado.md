---
agente: cursor
estado: pendiente
rama:
archivos: [.env.example, app/Models/User.php]
---

# U-04 + U-05: email real en .env.example + trait duplicado en User.php

Ver docs/auditorias/informe-unificado-y-plan.md (U-04, U-05).

## Qué hacer
1. En .env.example, busca cualquier email real (por ejemplo en
   MAIL_FROM_ADDRESS o similar) y reemplázalo por un placeholder genérico
   como noreply@example.com.
2. En app/Models/User.php, el trait HasRoles está declarado dos veces
   (use Notifiable; use HasRoles; use HasRoles;). Elimina la línea
   duplicada, deja solo una.
3. Ejecuta php artisan test: las 169 deben seguir pasando.

## Entregable
Añade una sección a docs/auditorias/informe-unificado-y-plan.md
confirmando ambos cambios.

## Restricciones
- Solo esos dos archivos. No toques .env real ni dependencias.
- Al terminar, vuelve con git switch --detach origin/main.
