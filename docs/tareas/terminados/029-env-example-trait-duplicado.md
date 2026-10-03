---
agente: cursor
estado: terminado
rama: ia/cursor/env-example-trait-duplicado
archivos: [.env.example, app/Models/User.php, docs/auditorias/informe-unificado-y-plan.md]
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

## Handoff
- Agente y fecha: Cursor, 2026-10-03
- Qué se hizo: placeholder `noreply@example.com` en `MAIL_USERNAME` y
  `MAIL_FROM_ADDRESS` de `.env.example`; eliminada la segunda
  `use HasRoles;` en `User.php`. Documentado en §11 del informe unificado.
- Archivos modificados:
  - `.env.example`
  - `app/Models/User.php`
  - `docs/auditorias/informe-unificado-y-plan.md` (sección 11)
  - `docs/tareas/terminados/029-env-example-trait-duplicado.md`
- Cómo probarlo: `php artisan test`; comprobar que `.env.example` no
  contiene `rulos26@gmail.com` y que `User.php` declara `HasRoles` una vez.
- Verificación ejecutada: `php artisan test` → 169 passed (510 assertions);
  `composer validate` → valid.
- Pendientes y riesgos: sigue existiendo `use Notifiable;` redundante
  (línea aparte además de `use HasFactory, Notifiable;`); fuera del alcance
  de U-05 (solo pedía HasRoles).
- Preguntas para el humano: ninguna.
