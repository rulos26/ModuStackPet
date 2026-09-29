---
agente: codex
estado: terminada
rama: ia/codex/auditoria-seguridad-l13
archivos: [docs/auditorias/]
---

# Auditoría de seguridad post-Laravel 13 (SOLO LECTURA)

El proyecto migró de Laravel 11 -> 12 -> 13 con una auditoría de seguridad
profunda ya realizada sobre la base de Laravel 11/12 (ver
docs/auditorias/seguridad-predespliegue.md y seg001 a seg021). Esta tarea
NO repite esa auditoría: verifica que sus hallazgos siguen corregidos tras
la migración, y busca huecos NUEVOS introducidos por Laravel 13 o por
dependencias que subieron de versión mayor en la Fase 3 (ver
docs/auditorias/fase3-laravel13.md para la lista completa de paquetes).

## Qué revisar
1. Repasa rápidamente (no reescribas) que los controles ya corregidos
   siguen intactos: rol Superadmin en /superadmin, MascotaPolicy, cifrado
   de credenciales en BD, CheckModuleStatus fail-closed, herramientas web
   desactivadas fuera de local. Si alguno se rompió con la migración,
   repórtalo como CRÍTICO.
2. Dependencias que subieron de versión MAYOR en la Fase 3 y su superficie
   de riesgo: laravel/socialite, laravel/fortify, laravel/passkeys (nuevo),
   spatie/laravel-permission, phpseclib, guzzlehttp/guzzle. Busca cambios
   de comportamiento por defecto que afecten seguridad (por ejemplo,
   cambios en validación, cookies, tokens).
3. config/session.php con serialization=json (recién activado): busca
   cualquier lugar del código que dependa de la serialización PHP anterior.
4. Revisa si `laravel/passkeys` (dependencia nueva, no usada explícitamente
   por la app) expone alguna ruta o endpoint por autodescubrimiento de
   Laravel sin que el proyecto lo haya configurado.
5. Cualquier nuevo default de seguridad de Laravel 13 (busca en la guía
   oficial de upgrade y en el CHANGELOG) que el proyecto no esté
   aprovechando y debería.

## Entregable
`docs/auditorias/seg023-seguridad-post-l13.md`: tabla de hallazgos
(ID | severidad | hallazgo | evidencia archivo:línea | corrección propuesta),
y una sección explícita "Controles previos verificados: intactos/rotos".

## Restricciones
- SOLO LECTURA: no modifiques código, configuración ni dependencias.
- No leas `.env`. No ejecutes seeders, migraciones ni backups.
- Al terminar, vuelve con `git switch --detach origin/main`.

## Handoff
- Agente y fecha: Codex, 2026-09-28.
- Qué se hizo: auditoría independiente de controles previos, cambios oficiales
  de Laravel 13, sesiones JSON, passkeys y dependencias sensibles. No se halló
  una regresión crítica. Se reportaron dos hallazgos medios: falta adoptar
  `cache.serializable_classes=false` y AutoClean elude el interruptor local
  (hueco preexistente, no causado por Laravel 13); además, falta cobertura de
  flujos OAuth/2FA.
- Archivos modificados:
  `docs/auditorias/seg023-seguridad-post-l13.md` y este archivo de tarea.
- Cómo probarlo: `php artisan test` — 144 pruebas y 436 aserciones correctas;
  `composer validate` — válido; `composer audit --locked --format=json` — cero
  avisos y cero paquetes abandonados; `php artisan route:list` — sin endpoints
  passkey/WebAuthn.
- Pendientes y riesgos: aplicar las correcciones requiere una tarea posterior;
  no se cambió código/configuración por la restricción de solo lectura. OAuth y
  2FA siguen requiriendo pruebas de integración y verificación manual antes de
  desplegar.
- Preguntas para el humano: ninguna; los tres trabajos sugeridos están
  priorizados al final del informe para la fase de unificación.
