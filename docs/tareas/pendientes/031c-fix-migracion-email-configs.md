---
agente: claude
estado: pendiente
rama: ia/claude/fortify-auth
archivos: [database/migrations/]
---

# Ajuste a la 031b: la migración no detecta password vacío

Investigación del humano: la fila activa de email_configs tiene
password = '' (cadena vacía), no un valor cifrado corrupto. La migración
2026_10_03_000000_deactivate_undecryptable_email_configs.php tiene la
condición where('password', '!=', '') que EXCLUYE esa fila de la
revisión, por eso nunca se desactivó y el warning sigue apareciendo.

## Qué hacer
1. Reproduce el bug tú mismo primero: confirma con tinker que
   DB::table('email_configs')->where('is_active',1)->first()->password
   es efectivamente ''.
2. Corrige la migración (no crees una nueva, ajusta la misma de la 031b,
   ya que no se ha fusionado a main todavía): quita la condición
   where('password', '!=', '') o cámbiala para que SÍ incluya cadenas
   vacías en la revisión. Decide si una password vacía debe tratarse
   igual que una no descifrable (desactivar la fila) y aplícalo.
3. Verifica localmente en este worktree (worktree-claude, no la carpeta
   principal) ejecutando la migración contra una copia de la base real:
   el conteo de EmailConfig::where('is_active',1)->count() debe dar 0
   después de migrar, y el warning no debe volver a aparecer en el log
   tras una petición normal.
4. Actualiza el entregable docs/auditorias/seg031-fortify-auth.md con la
   causa raíz real (password vacío, no clave incorrecta) y la corrección.

## Entregable
Sección actualizada en seg031-fortify-auth.md con la causa raíz correcta.

## Restricciones
- No toques .env ni dependencias. Las 169 pruebas deben seguir pasando.
- Al terminar, vuelve con git switch --detach origin/main.
