---
agente: claude
estado: pendiente
rama:
archivos: [app/Models/ModuleLog.php, tests/, .env.testing, AGENTS.md]
---

# Corregir ModuleLog para usuarios anónimos y eliminar advertencias de pruebas

Ambas correcciones fueron propuestas en docs/auditorias/diagnostico-pruebas.md
y APROBADAS por el humano.

## 1. ModuleLog::createLog() con usuario anónimo
- Cambia el parámetro de usuario a nullable (`?int`), sin cambiar su posición.
- Busca todas las llamadas (`grep -rn "createLog" app/`) y reemplaza cualquier
  uso de 0 como "sin usuario" (p. ej. `auth()->id() ?? 0`) por null.
- Confirma en las migraciones que `module_logs.user_id` admite null.
  Si NO lo admite, detente y repórtalo: no crees ni modifiques migraciones.
- En las pruebas donde se usó `createQuietly()` solo para evitar este error,
  vuelve a `create()` para que los observers se ejecuten de nuevo. Si alguno
  debe seguir con `createQuietly()`, explica el motivo en el Handoff.

## 2. .env.testing sin secretos
- Crea `.env.testing` con solo comentarios que indiquen que la configuración
  de pruebas vive en phpunit.xml. Sin variables ni secretos.
- Verifica que git NO lo ignore: `git check-ignore -v .env.testing` debe
  devolver nada. Si lo ignora, repórtalo antes de cambiar .gitignore.
- Añade a AGENTS.md: ".env.testing es la única excepción a la regla de .env:
  se versiona, y nunca debe contener secretos".

## Resultado esperado
`php artisan test`: 0 fallidas, 0 advertencias. Si no se logra, documenta
exactamente qué queda y por qué.

## Restricciones
- No toques `.env`, migraciones, dependencias ni otros archivos de app/.
- No desactives pruebas ni cambies lo que verifican.
