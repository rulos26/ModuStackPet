# Auditoría de dependencias y stack tecnológico — ModuStackPet

**Fecha:** 2026-09-27  
**Alcance:** repositorio completo (solo lectura)  
**Auditor:** automatizado según `docs/prompt/sin realizar/auditoria-deps.md`

---

## 1. Resumen ejecutivo

El stack es **Laravel 11 + PHP 8.2 + MySQL 8.0 + Vite 6**, con lockfiles versionados. El estado general es **riesgo alto**: Laravel 11 y MySQL 8.0 ya están **fuera de soporte de seguridad** (EOL), y `.env.example` versionado contiene secretos reales (APP_KEY, BD, correo).

| Severidad | Cantidad |
|-----------|----------|
| CRÍTICA   | 4 |
| ALTA      | 5 |
| MEDIA     | 5 |
| BAJA      | 2 |

**3 acciones más urgentes**

1. Rotar y eliminar secretos de `.env.example` (APP_KEY, DB_*, MAIL_*); asumir compromiso si el repo es/fue público.
2. Planificar migración **Laravel 11 → 12 LTS o 13** (11 EOL desde 2026-03-12; sin parches para CVEs nuevos de la línea 12/13).
3. Sustituir **MySQL 8.0** (EOL 2026-04-30) por **8.4 LTS** (o superior soportado) y pinnear tags Docker.

---

## 2. Inventario del stack

| Componente | Versión actual | Última estable (fuente) | Fin de soporte | Estado |
|------------|----------------|-------------------------|----------------|--------|
| PHP (constraint) | `^8.2` (`composer.json` / `composer.lock` platform) | 8.5.10 (endoflife.date) | 8.2 security EOL **2026-12-31** | Activo soporte de seguridad; soporte activo ya terminó 2024-12-31 |
| PHP (Docker) | imagen `php:8.2-fpm-alpine` (tag flotante de minor) | — | igual ciclo 8.2 | A VERIFICAR patch exacto en runtime |
| Laravel Framework | **v11.44.2** (`composer.lock`) | **v13.33.0** / última 11.x **v11.56.1** (Packagist) | 11: soporte activo 2025-09-03; security EOL **2026-03-12** | **EOL** |
| MySQL (Compose) | `mysql:8.0` | 8.0.46 / 8.4.11 LTS (endoflife.date) | 8.0 EOL **2026-04-30** | **EOL** |
| Nginx (Compose) | `nginx:1.27-alpine` | 1.31.6 (endoflife.date) | 1.27 EOL **2025-06-24** | **EOL** |
| Node (host auditoría) | v24.21.0 | 24.21.0 LTS (endoflife.date) | Node 24 EOL 2028-04-30 | OK en host; no hay `.nvmrc` |
| npm (host) | 11.19.0 | — | — | OK |
| Vite | 6.0.7 (`package-lock.json`) | 8.3.1 (npmjs) | — | Desactualizado (dev) |
| Axios | 1.7.9 (`package-lock.json`) | 1.20.0 (npmjs) | — | Vulnerable (dev) |
| Fortify | v1.25.4 | v1.40.0 (Packagist) | — | Atraso menor/mayor |
| Socialite | v5.23.1 | v5.31.0 (Packagist) | — | Atraso menor |
| Spatie Permission | 6.17.0 | 8.3.0 (Packagist) | — | 2 majors atrás |
| DomPDF | v3.1.1 | v3.1.2 (Packagist) | — | Parche pendiente |
| Composer | no disponible en PATH | — | — | A VERIFICAR |
| CI/CD | no hay `.github/workflows` | — | — | Ausente |

**Lockfiles / secretos**

| Ítem | Evidencia |
|------|-----------|
| `composer.lock` versionado | `git ls-files` → presente |
| `package-lock.json` versionado | `git ls-files` → presente |
| `.env` en `.gitignore` | `.gitignore` línea con `.env` |
| `.env.example` versionado | `git ls-files` → presente; **contiene valores secretos** |

---

## 3. Hallazgos

| ID | Severidad | Componente | Problema | Evidencia | Acción recomendada |
|----|-----------|------------|----------|-----------|-------------------|
| DEP-001 | CRÍTICA | `.env.example` | Secretos reales versionados: `APP_KEY`, `DB_DATABASE`/`DB_USERNAME`/`DB_PASSWORD`, `MAIL_USERNAME`/`MAIL_PASSWORD` (valores no reproducidos aquí) | Lectura de `.env.example` (versionado en git) | Rotar todos los secretos afectados; limpiar `.env.example` a placeholders; revisar historial git / exposición del repo |
| DEP-002 | CRÍTICA | Laravel 11 | Framework **EOL** (security ended 2026-03-12). Última 11.x en Packagist es 11.56.1; lock en 11.44.2 | endoflife.date/laravel; Packagist `laravel/framework`; `composer.lock` v11.44.2 | Migrar a Laravel **12** (PHP 8.2–8.5) o **13** (PHP 8.3–8.5) siguiendo guía oficial |
| DEP-003 | CRÍTICA | CVE-2026-48019 / GHSA-5vg9-5847-vvmq | CRLF en validación `email`; parches solo en **12.60.0+** y **13.10.0+**. Rangos publicados incluyen `< 12.60.0` (cubre 11.x). **No hay patch en línea 11** | Tenable CVE-2026-48019; GHSA-5vg9-5847-vvmq; SentinelOne | Upgrade mayor a ≥12.60 o ≥13.10; mitigar validando rechazo de `\r`/`\n` en emails de usuario |
| DEP-004 | CRÍTICA | MySQL 8.0 | Imagen `mysql:8.0` en Compose; ciclo 8.0 **EOL 2026-04-30** | `docker-compose.yml`; endoflife.date/mysql | Migrar a `mysql:8.4` (LTS) o política de versión soportada; pinnear digest/tag menor |
| DEP-005 | ALTA | Nginx 1.27 | `nginx:1.27-alpine` con EOL **2025-06-24** | `docker-compose.yml`; endoflife.date/nginx | Actualizar a rama mainline/stable soportada (p. ej. 1.30/1.31) con tag fijo |
| DEP-006 | ALTA | PHP 8.2 | Soporte activo terminó; security EOL **2026-12-31** (~3 meses) | endoflife.date/php; `Dockerfile` `php:8.2-fpm-alpine` | Planificar PHP 8.3/8.4 alineado con Laravel objetivo; pinnear patch en Docker |
| DEP-007 | ALTA | Config prod vía example | `APP_DEBUG=true`, `APP_ENV=local`, `SESSION_SECURE_COOKIE` no definido, `SESSION_ENCRYPT=false` | `.env.example`; `config/session.php` (`secure` ← env) | Documentar valores seguros de producción; nunca copiar example tal cual a prod |
| DEP-008 | ALTA | npm (dev) axios/vite/postcss | `npm audit`: **17** hallazgos (2 critical, 11 high). Directos: axios (high), vite (high), postcss (high). Lock: axios 1.7.9, vite 6.0.7 | `npm audit --json`; `package-lock.json` | Actualizar axios ≥1.15.2 / vite a línea parcheada; `npm audit --omit=dev` = 0 vulns (impacto runtime prod limitado si no se sirve toolchain) |
| DEP-009 | ALTA | `node_modules` ausente | `npm ls` reporta UNMET DEPENDENCY en todos los directos; `Test-Path node_modules` = False | `npm ls --depth=0` | Ejecutar `npm ci` en entornos de build (no hecho en esta auditoría: solo lectura / sin install) |
| DEP-010 | MEDIA | spatie/laravel-permission | 6.17.0 vs 8.3.0 (2 majors) | Packagist; `composer.lock` | Evaluar upgrade mayor tras Laravel; revisar breaking changes Spatie |
| DEP-011 | MEDIA | Fortify / Socialite / DomPDF | Atraso vs Packagist (1.25.4→1.40.0; 5.23.1→5.31.0; 3.1.1→3.1.2) | Packagist; lock | Tras subir Laravel, `composer update` acotado de estos paquetes |
| DEP-012 | MEDIA | nunomaduro/larastan | Marcado **abandoned** → `larastan/larastan` | `composer.lock` `"abandoned": "larastan/larastan"` | Reemplazar por `larastan/larastan` en require-dev |
| DEP-013 | MEDIA | Docker tags flotantes | `php:8.2-fpm-alpine`, `mysql:8.0`, `nginx:1.27-alpine` sin digest; Compose con passwords débiles de lab | `Dockerfile`, `docker-compose.yml` | Pinnear digests; no reutilizar credenciales de compose en prod |
| DEP-014 | MEDIA | Sin CI de auditoría | No hay workflows GitHub Actions | Glob `.github/workflows/*` vacío | Añadir job `composer audit` + `npm audit` en CI |
| DEP-015 | BAJA | axios en `devDependencies` | Correcto para Vite; si se importa en frontend, el bundle arrastra CVEs de axios 1.7.9 | `package.json` | Confirmar uso en assets; si se usa en browser, tratar update como Fase 0 |
| DEP-016 | BAJA | Pest plugin allow-plugins | `pestphp/pest-plugin: true` sin Pest en require | `composer.json` `config.allow-plugins` | Quitar entrada innecesaria |

**Notas CVE Laravel 11 ya mitigados en lock actual**

| CVE / GHSA | Parche | Lock 11.44.2 |
|------------|--------|--------------|
| CVE-2025-27515 / GHSA-78fx-h6xr-vch4 | 11.44.1 | Cubierto (≥11.44.1) |
| CVE-2024-13918 / CVE-2024-13919 | 11.36.0 | Cubierto |

---

## 4. Matriz de compatibilidad y bloqueos

| Destino | PHP | Notas / bloqueos |
|---------|-----|------------------|
| Quedarse en Laravel 11.56.x | 8.2–8.4 | Solo gana parches históricos de la línea 11; **no** corrige CVE-2026-48019 (sin release 11) |
| Laravel 12 | 8.2–8.5 | Camino más natural desde PHP 8.2 actual; exigir ≥12.60.0 por CVE-2026-48019 |
| Laravel 13 | 8.3–8.5 | Requiere subir PHP (≥8.3); última estable 13.33.0; exigir ≥13.10 (≥13.12 si aplica GHSA signed URLs en 13) |
| Spatie Permission 7/8 | Según docs Spatie | Bloqueado hasta estabilizar major de Laravel |
| Vite 7/8 | Node moderno | Major jump; probar `laravel-vite-plugin` compatible |
| MySQL 8.4 | Driver PDO mysql | Revisar SQL modes / auth plugin en hosting |

**Bloqueo principal:** EOL de Laravel 11 + ausencia de patch para CVE-2026-48019 en la línea 11 obliga a **major upgrade** del framework (no basta `composer update` menor dentro de ^11).

---

## 5. Plan de actualización por fases

### Fase 0 — Inmediata (seguridad sin major de framework)

| Acción | Riesgo | Esfuerzo | Pruebas |
|--------|--------|----------|---------|
| Rotar APP_KEY, DB, MAIL y cualquier secreto filtrado; sanitizar `.env.example` | Bajo (ops) | Bajo | Login, mail, encriptación |
| Mitigación temporal CRLF en emails (rechazar `\r`/`\n`) | Bajo | Bajo | Registro, reset password, notificaciones |
| `npm update axios vite postcss` (o bump mínimo que cierre audit) + `npm audit` | Bajo–medio | Bajo | `npm run build`, smoke UI |
| Producción: `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true` (HTTPS) | Bajo | Bajo | Cookies / HTTPS |

### Fase 1 — Menores / patch de Composer (aún en 11)

| Acción | Riesgo | Esfuerzo | Pruebas |
|--------|--------|----------|---------|
| Subir a última 11.x (11.56.1) + Fortify/Socialite/DomPDF patch | Medio | Medio | Suite PHPUnit, auth Fortify/Socialite, PDF |
| Reemplazar `nunomaduro/larastan` → `larastan/larastan` | Bajo | Bajo | `phpstan`/`larastan` |

### Fase 2 — Majors de framework y paquetes

| Acción | Riesgo | Esfuerzo | Pruebas |
|--------|--------|----------|---------|
| Laravel 11 → **12.69.x** (≥12.60) **o** 13.x (≥13.12) con guía [Upgrade Guide](https://laravel.com/docs/upgrade) | Alto | Alto | Regresión completa, 2FA Fortify, permisos Spatie, Socialite |
| Spatie Permission 6 → 7/8 | Alto | Medio–alto | Roles/permisos, policies |
| Vite 6 → 7/8 + plugin Laravel | Medio | Medio | Build assets |

### Fase 3 — Runtime / infra EOL

| Acción | Riesgo | Esfuerzo | Pruebas |
|--------|--------|----------|---------|
| PHP 8.2 → 8.3/8.4 (según Laravel elegido); pin Docker | Medio | Medio | CI + staging |
| MySQL 8.0 → 8.4 LTS; Nginx a rama soportada | Medio | Medio | Backup, migrate, smoke SQL |
| Añadir CI: `composer audit`, `npm audit --omit=dev` | Bajo | Bajo | Pipeline verde |

---

## 6. Ítems A VERIFICAR

1. **`composer audit` / `composer outdated` / `composer validate`:** PHP y Composer no están en PATH; Docker CLI existe (29.8.0) pero el **daemon no responde** (`dockerDesktopLinuxEngine` pipe ausente). No se pudo ejecutar audit Composer oficial.
2. **Versión exacta de PHP en producción / contenedor local** (solo se conoce el tag de imagen).
3. **Versión exacta de MySQL/Nginx en runtime** (tags mayores flotantes).
4. **Si producción usa el mismo contenido que `.env.example`** (rotación urgente si sí).
5. **Exposición del historial git** de secretos (si el remoto es público o fue compartido).
6. **Uso real de axios en el bundle del navegador** vs solo tooling.
7. **Hosting de producción** (¿MySQL gestionado ya en 8.0 EOL?).
8. **Aplicabilidad exacta de CVE-2026-48019 a 11.44.2:** el advisory lista `< 12.60.0` y no publica patch 11; confirmar con GHSA oficial si 11 está en alcance (tratar como afectado por precaución).

---

## 7. Anexo — comandos y resultados

| Comando | Resultado |
|---------|-----------|
| `git ls-files composer.lock package-lock.json .env.example` | Lockfiles y example versionados; `.env` no trackeado |
| Lectura `composer.json` / `composer.lock` / `package.json` / `package-lock.json` | Stack Laravel 11 / Vite 6 inventariado |
| Lectura `Dockerfile`, `docker-compose.yml` | PHP 8.2-fpm-alpine, nginx 1.27, mysql 8.0 |
| Lectura `.env.example` | Secretos presentes (no volcados en informe) |
| `php -v` | **FALLÓ** — `php` no en PATH |
| `composer --version` / `composer audit` | **FALLÓ** — Composer no en PATH |
| `docker run … composer:2 …` | **FALLÓ** — daemon Docker no disponible |
| `node -v` / `npm -v` | v24.21.0 / 11.19.0 |
| `npm ls --depth=0` | UNMET (sin `node_modules`) |
| `npm outdated --json` | `{}` (sin modules instalados / sin comparación útil) |
| `npm audit --omit=dev --json` | 0 vulnerabilidades |
| `npm audit --json` | 17 vulns (2 critical, 11 high, 3 moderate, 1 low) |
| `https://endoflife.date/api/{php,laravel,mysql,nginx,nodejs}.json` | EOL confirmados |
| Packagist `p2` laravel/framework, fortify, socialite, permission, dompdf | Últimas versiones citadas en inventario |
| npmjs `axios@latest`, `vite@latest` | 1.20.0 / 8.3.1 |
| Búsqueda advisories Laravel / CVE-2026-48019 | GHSA-5vg9-5847-vvmq, etc. |

**Archivos auxiliares de esta sesión (a borrar):** `_audit_parse_lock.cjs` (script temporal de parseo).
