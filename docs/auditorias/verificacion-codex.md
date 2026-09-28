# Verificación independiente de la auditoría de dependencias

**Fecha:** 2026-09-27  
**Documento verificado:** `docs/auditorias/auditoria-deps-cursor-local.md`  
**Alcance:** manifiestos, lockfiles y archivos Docker indicados en la tarea; consultas a fuentes oficiales. No se instaló ni actualizó ningún paquete y no se leyó `.env`.

## Resumen

La auditoría original es fiable en el inventario de versiones, ciclos de soporte, ausencia de `node_modules`, deuda de actualización y riesgos de los tags Docker. También acierta sobre CVE-2026-48019: el advisory oficial existe y comprende Laravel 11.44.2.

La principal incorrección es DEP-001: `.env.example` no contiene un `APP_KEY` ni contraseñas con valor. Sí contiene identificadores no vacíos que deben mantenerse genéricos, pero la evidencia no permite llamarlos “secretos reales” ni justificar por sí sola una severidad crítica. La cifra de `npm audit` sí se reproduce, aunque es una foto temporal y actualmente engloba muchos advisories adicionales bajo los mismos 17 paquetes afectados.

## Verificación de hallazgos

| ID original | Veredicto | Evidencia | Comentario |
|---|---|---|---|
| DEP-001 | **REFUTADO** | `.env.example:8,55,92`: `APP_KEY`, `DB_PASSWORD` y `MAIL_PASSWORD` están vacíos; comprobación local por nombre y estado, sin reproducir valores. `.env.example:53-54,91` tiene identificadores no vacíos. | No se probaron “secretos reales” ni hay credenciales completas utilizables. Conviene sustituir también los identificadores por placeholders si corresponden a infraestructura real, pero rotar `APP_KEY`/contraseñas no se deriva de este archivo. |
| DEP-002 | **CONFIRMADO** | `composer.lock:1522-1523` fija `laravel/framework` v11.44.2. La [política oficial de Laravel 11](https://laravel.com/framework/docs/11.x/releases) terminó correcciones de seguridad el 2026-03-12. | Laravel 11 está fuera de soporte de seguridad. Packagist muestra una línea 11 más nueva, pero actualizar dentro de 11 no restablece soporte. |
| DEP-003 | **CONFIRMADO** | `composer.lock:1523` = v11.44.2. El [advisory oficial GHSA-5vg9-5847-vvmq](https://github.com/laravel/framework/security/advisories/GHSA-5vg9-5847-vvmq) identifica CVE-2026-48019, afecta `<12.60.0` y `<=13.9.0`, y solo publica parches para 12/13. [FriendsOfPHP](https://github.com/FriendsOfPHP/security-advisories/blob/master/laravel/framework/CVE-2026-48019.yaml) enumera expresamente toda la rama 11.x como afectada y sin fecha de parche. | El CVE existe; no debe marcarse como inventado. La aplicabilidad exacta depende además de que la aplicación envíe correo a direcciones controladas por usuario, pero el paquete bloqueado está en rango vulnerable. |
| DEP-004 | **CONFIRMADO** | `docker-compose.yml:41` usa `mysql:8.0`. Las [notas oficiales de MySQL 8.0](https://docs.oracle.com/cd/E17952_01/mysql-8.0-relnotes-en/mysql-8.0-relnotes-en.pdf) indican EOL en abril de 2026; la [política de Oracle](https://www.oracle.com/us/assets/lifetime-support-technology-069183.pdf) sitúa el fin de soporte extendido en abril de 2026. | El día exacto 30 no aparece en la tabla de política, pero sí el mes y el EOL de la rama. |
| DEP-005 | **CONFIRMADO** | `docker-compose.yml:27` usa `nginx:1.27-alpine`; la [página oficial de descargas](https://nginx.org/en/download.html) solo presenta 1.31 como mainline, 1.30 como estable y ramas pares como legacy; 1.27 ya no figura. | Se confirma que 1.27 es una rama mainline antigua. La fecha exacta de EOL citada por la auditoría procede de una fuente secundaria, no de una política oficial de Nginx. |
| DEP-006 | **CONFIRMADO** | `Dockerfile:1` usa `php:8.2-fpm-alpine`. [PHP Supported Versions](https://www.php.net/supported-versions.php) indica fin de soporte activo el 2024-12-31 y fin de seguridad el 2026-12-31. | El riesgo de cercanía al EOL está correctamente descrito; el tag no fija patch ni digest. |
| DEP-007 | **CONFIRMADO** | `.env.example:5,11,60` define entorno local, debug habilitado y cifrado de sesión deshabilitado; no existe `SESSION_SECURE_COOKIE`. `config/session.php:50,173` toma `SESSION_ENCRYPT` y `SESSION_SECURE_COOKIE` del entorno. | Son valores de desarrollo, no evidencia de configuración de producción. La recomendación de documentar overrides seguros para producción es válida. |
| DEP-008 | **CONFIRMADO** | `package-lock.json:888,2491` fija axios 1.7.9 y Vite 6.0.7; PostCSS aparece desde `package-lock.json:1865`. Ejecutado `npm audit --json` el 2026-09-27: **17** paquetes afectados (2 critical, 11 high, 3 moderate, 1 low); directos axios, Vite y PostCSS. `npm audit --omit=dev --json`: 0. | La cifra se reproduce. Es temporal: el detalle de advisories cambió desde la redacción, aunque el agregado sigue igual. `package.json:8-15` clasifica todo el toolchain como dev dependency. |
| DEP-009 | **CONFIRMADO** | `Test-Path node_modules` = `False`; `npm ls --depth=0` devolvió siete `UNMET DEPENDENCY`, uno por cada dependencia directa. | Describe el checkout local, no una vulnerabilidad ni un defecto del repositorio. Severidad alta no está justificada por sí sola. |
| DEP-010 | **CONFIRMADO** | `composer.lock:4313-4314` fija Spatie Permission 6.17.0; [Packagist](https://packagist.org/packages/spatie/laravel-permission) publica 8.3.0 y muestra que 8.x requiere PHP ^8.3 y Laravel 12/13. | Está dos majors atrás, pero 8.x no es compatible con el stack actual; la auditoría acierta al posponerlo tras Laravel/PHP. |
| DEP-011 | **CONFIRMADO** | Locks: Fortify v1.25.4 (`composer.lock:1457-1458`), Socialite v5.23.1 (`1857-1858`) y wrapper DomPDF v3.1.1 (`64-65`). Versiones publicadas: [Fortify 1.40.0](https://packagist.org/packages/laravel/fortify), [Socialite 5.31.0](https://packagist.org/packages/laravel/socialite), [laravel-dompdf 3.1.2](https://packagist.org/packages/barryvdh/laravel-dompdf). | Se confirma el atraso. “Menor/mayor” es ambiguo: Fortify sigue en major 1, Socialite en 5 y el wrapper DomPDF en 3. |
| DEP-012 | **CONFIRMADO** | `composer.lock:7641-7642,7735` fija `nunomaduro/larastan` v3.3.1 y declara `"abandoned": "larastan/larastan"`. | El reemplazo está explícitamente indicado por el lock. |
| DEP-013 | **CONFIRMADO** | `Dockerfile:1,12` usa `php:8.2-fpm-alpine` y `composer:2`; `docker-compose.yml:27,41` usa `nginx:1.27-alpine` y `mysql:8.0`, todos sin digest. `docker-compose.yml:19,47-48,52` contiene credenciales locales triviales y expone la de root en el argumento del healthcheck. | Correcto para un laboratorio aislado, inadecuado como configuración reutilizable en producción. La auditoría omitió `composer:2` al enumerar tags flotantes. |
| DEP-014 | **CONFIRMADO** | `git ls-files .github/workflows` no devolvió archivos. | No hay CI versionada para ejecutar auditorías. Esto es una carencia de proceso, no una vulnerabilidad directa. |
| DEP-015 | **CONFIRMADO** | `package.json:10` declara axios en devDependencies; `resources/js/bootstrap.js:1-4` lo importa, lo asigna a `window.axios` y configura sus cabeceras. | No es solo tooling: axios entra en el bundle del navegador cuando `bootstrap.js` forma parte del entrypoint, por lo que su actualización debe priorizarse. |
| DEP-016 | **CONFIRMADO** | `composer.json:70-73` permite `pestphp/pest-plugin`; no aparece en `require`, `require-dev` ni `composer.lock` (`rg` sin coincidencias en el lock). | Entrada actualmente innecesaria. Su impacto es bajo, pero conviene eliminarla cuando se permita modificar dependencias/configuración. |

## Hallazgos nuevos

### NUEVO-001 — Otro advisory oficial sin parche para Laravel 11

El [advisory oficial GHSA-crmm-hgp2-wgrp](https://github.com/laravel/framework/security/advisories/GHSA-crmm-hgp2-wgrp) (CVE-2026-48041, severidad moderada) afecta `<12.61.1` y `<13.12.0`. `composer.lock:1523` fija 11.44.2 y el advisory no publica parche para 11. La auditoría lo alude de forma incidental en la matriz (“signed URLs”), pero no lo inventaría como hallazgo ni lo incluye en el resumen de riesgo.

Aplicabilidad: requiere uso de URLs temporales firmadas del driver local. Debe revisarse el código/configuración antes de afirmar exposición explotable.

### NUEVO-002 — Advisory XSS reciente condicionado por `APP_DEBUG=true`

El [advisory oficial GHSA-jh5r-qr3c-85q8](https://github.com/laravel/framework/security/advisories/GHSA-jh5r-qr3c-85q8), publicado el 2026-09-10, afecta `<12.69.0` y `<13.30.0`; no ofrece parche para Laravel 11. Describe XSS en la página de debug cuando `APP_DEBUG=true`. El lock usa 11.44.2 y `.env.example:11` habilita debug.

Aplicabilidad: queda **NO VERIFICABLE en producción**, porque no se leyó `.env` y el example no demuestra el valor desplegado. Sí refuerza la recomendación de mantener debug desactivado fuera de desarrollo.

### NUEVO-003 — El Dockerfile oculta fallos de instalación

`Dockerfile:17` ejecuta `composer install --no-scripts || true` y `Dockerfile:20` termina otra cadena con `|| true`. Una resolución fallida de dependencias puede producir una imagen “exitosa” pero sin un `vendor/` completo o con permisos incorrectos. Además, `--no-scripts` evita `package:discover`, por lo que la imagen no equivale a una instalación Laravel normal. Este problema de reproducibilidad no fue registrado como hallazgo.

Recomendación: cuando se autorice modificar la imagen, retirar los `|| true`, separar las etapas que puedan fallar y ejecutar una instalación reproducible basada en `composer.lock`.

### NUEVO-004 — La auditoría Composer sigue incompleta

No hay ejecutable `php` ni `composer` en PATH y no se instaló software por restricción de la tarea. Por tanto, no se pudo ejecutar `composer validate` ni `composer audit`; las consultas puntuales a advisories de Laravel no sustituyen una auditoría de **todo** el grafo de Composer. Este riesgo ya figuraba como “A VERIFICAR”, pero debe considerarse una limitación material de la conclusión “4 críticas / 5 altas”.

## Conclusión

Son fiables el inventario del stack y lockfiles, los EOL de Laravel/MySQL, la proximidad al EOL de PHP 8.2, el estado antiguo de Nginx, el abandono de `nunomaduro/larastan`, la ausencia de CI y `node_modules`, los tags flotantes y el resultado reproducido de `npm audit`. CVE-2026-48019 es auténtico y Laravel 11.44.2 está afectado según las bases oficiales consultadas.

No es fiable la afirmación crítica de secretos completos en `.env.example`; debe corregirse antes de usar el informe para ordenar una rotación de credenciales. Tampoco debe interpretarse el total de severidades como exhaustivo: falta `composer audit`, se mezclan riesgos de producción con condiciones puramente locales y al menos dos advisories oficiales de Laravel no aparecen como hallazgos formales.

## Comandos de verificación relevantes

- `npm audit --json` → 17 paquetes afectados: 2 critical, 11 high, 3 moderate, 1 low.
- `npm audit --omit=dev --json` → 0 vulnerabilidades.
- `npm ls --depth=0` → siete dependencias directas ausentes (`ELSPROBLEMS`).
- `git ls-files composer.lock package-lock.json .env.example .github/workflows` → ambos lockfiles y `.env.example` versionados; ningún workflow.
- Parseo local de `composer.lock` y `package-lock.json` con Node.js → versiones citadas en la tabla.
- `rg` sobre manifiestos, locks, Docker y `resources/js/bootstrap.js` → líneas citadas.

No se ejecutaron `php artisan test` ni `composer validate` porque PHP y Composer no están disponibles en PATH. No se utilizó Docker para suplirlos y no se instalaron dependencias.
