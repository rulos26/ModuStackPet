---
description: Auditoría de dependencias y stack tecnológico (solo lectura)
argument-hint: [ruta o módulo a auditar, opcional]
---

# Auditoría de dependencias y stack tecnológico

## Rol y objetivo

Actúa como auditor senior de seguridad y mantenimiento de software para un proyecto
en producción. Tu objetivo es producir un diagnóstico verificable del stack tecnológico
y sus dependencias, con un plan de actualización priorizado por riesgo.

Alcance: $ARGUMENTS (si está vacío, audita todo el repositorio).

## Reglas obligatorias

1. **Solo lectura.** No modifiques código, `composer.json`, `composer.lock`, `package.json`,
   `package-lock.json`, `pom.xml` ni ningún otro archivo del proyecto. No ejecutes
   `composer update`, `npm install`, `npm audit fix` ni equivalentes.
2. **No inventes datos.** Ninguna versión, CVE, fecha de EOL o advisory puede salir de tu
   memoria sin verificación. Si no puedes verificarlo con la salida de un comando o una
   fuente oficial, márcalo como `A VERIFICAR`.
3. **Fuentes válidas:** salida de los comandos ejecutados, packagist.org, npmjs.com,
   Maven Central, GitHub Security Advisories, NVD, endoflife.date y la página oficial de
   soporte de cada framework/motor.
4. **Secretos:** nunca muestres valores de `.env`, credenciales, tokens ni cadenas de
   conexión. Reporta solo si la variable existe y si su configuración es insegura.
5. **No instales herramientas** (por ejemplo OWASP Dependency-Check) sin pedirme permiso.
6. Si un comando falla, registra el error y continúa con el resto.
7. Si falta información para concluir, detente y pregúntame en lugar de suponer.

## Fase 1: descubrimiento del stack

Localiza y lista los archivos que definen el stack:

- PHP: `composer.json`, `composer.lock`, `.php-version`
- Node/Frontend: `package.json`, `package-lock.json` / `yarn.lock` / `pnpm-lock.yaml`,
  `.nvmrc`, `angular.json`
- Java: `pom.xml`, `build.gradle(.kts)`, `gradle.properties`, `.java-version`
- Infraestructura: `Dockerfile`, `docker-compose.yml`, `.tool-versions`
- CI/CD: `.github/workflows/*`, `.gitlab-ci.yml`, `Jenkinsfile`
- Configuración: `config/*.php`, `application*.yml|properties`, `.env.example`

Confirma: ¿los lockfiles están versionados en git? ¿El `.env` está en `.gitignore`?

## Fase 2: recolección de evidencia

Ejecuta solo los bloques que apliquen al stack detectado.

### PHP / Composer / Laravel
```bash
php -v
composer --version
composer validate --strict
composer show --direct --format=json
composer outdated --direct --format=json
composer outdated --major-only --direct
composer audit --format=json          # CVE y paquetes abandonados
composer config allow-plugins
php artisan about                     # Laravel 9.21+
```

### Node / Angular
```bash
node -v && npm -v
npm ls --depth=0
npm outdated --json
npm audit --omit=dev --json
npx ng version                        # si es Angular
```

### Java / Maven / Gradle
```bash
java -version
mvn -v
mvn -q versions:display-dependency-updates
mvn -q versions:display-plugin-updates
mvn -q dependency:tree -Dscope=runtime
# Gradle
./gradlew dependencies --configuration runtimeClasspath
```

### Base de datos
```sql
-- MySQL / MariaDB
SELECT VERSION();
-- PostgreSQL
SELECT version();
-- Oracle
SELECT banner FROM v$version;
```
Registra también la versión del driver (PDO/`ext-*`, `ojdbc`, `mysql-connector-j`, etc.).

### Contenedores
Lista las imágenes base de los `Dockerfile` y si usan tags fijos o `latest`.

## Fase 3: análisis

Evalúa cada punto y genera hallazgos con ID (`DEP-001`, `DEP-002`...).

**A. Runtime y framework.** Versión de PHP/Java/Node, framework y motor de BD.
Fecha de fin de soporte activo y de seguridad. ¿Está en EOL o a menos de 6 meses?

**B. Vulnerabilidades.** Para cada CVE/GHSA: paquete, versión afectada, severidad (CVSS),
si es dependencia directa o transitiva, versión que lo corrige y si la corrección
implica salto mayor.

**C. Paquetes abandonados o sin mantenimiento.** Marcados como `abandoned`, sin release
en más de 2 años o repositorio archivado. Propón reemplazo si existe.

**D. Obsolescencia.** Dependencias directas con una o más versiones mayores de atraso.

**E. Restricciones de versión.** Rangos peligrosos (`*`, `>=`, `dev-master`, ramas git)
o pines tan rígidos que bloquean parches de seguridad.

**F. Cadena de suministro.**
- `allow-plugins` con `true` global o plugins innecesarios.
- Scripts `postinstall`/`preinstall` en npm y `scripts` en Composer.
- Repositorios VCS, privados o sin HTTPS; `secure-http: false`.
- Lockfiles ausentes o no versionados.

**G. Matriz de compatibilidad.** PHP ↔ framework ↔ paquetes; Java ↔ Spring Boot ↔
Jakarta/javax; Node ↔ Angular/TypeScript; motor BD ↔ driver. Señala qué bloquea
cada actualización.

**H. Higiene de dependencias.** Paquetes de desarrollo en `require` / `dependencies`
que deberían estar en `require-dev` / `devDependencies`. Posibles dependencias sin uso
(indícalo como sospecha, sin instalar herramientas).

**I. Configuración de seguridad.**
- Laravel: `APP_DEBUG`, `APP_ENV`, `APP_KEY` definido, `SESSION_SECURE_COOKIE`,
  CORS, `TrustProxies`.
- Spring: endpoints de Actuator expuestos, perfiles activos, `spring.jpa.show-sql`.
- Secretos commiteados en el historial o en archivos de configuración.

**J. Consistencia entre entornos.** ¿Las versiones de runtime en Docker/CI coinciden
con las declaradas en el proyecto y con producción?

## Fase 4: priorización

| Severidad | Criterio |
|---|---|
| CRÍTICA | CVE explotable en dependencia de producción, o runtime/framework en EOL sin parches de seguridad |
| ALTA | CVE alto en transitiva de producción, paquete abandonado en ruta crítica, secretos expuestos |
| MEDIA | Versiones mayores de atraso, rangos peligrosos, riesgos de cadena de suministro |
| BAJA | Higiene (require vs require-dev), menores desactualizadas sin impacto de seguridad |

## Fase 5: entregable

Crea el archivo `AUDITORIA_DEPENDENCIAS.md` en la raíz con esta estructura:

1. **Resumen ejecutivo** (máximo 10 líneas): estado general, número de hallazgos por
   severidad y las 3 acciones más urgentes.
2. **Inventario del stack**: tabla `componente | versión actual | última estable |
   fin de soporte | estado`.
3. **Hallazgos**: tabla `ID | severidad | componente | problema | evidencia | acción
   recomendada`. La columna evidencia cita el comando o fuente.
4. **Matriz de compatibilidad** y bloqueos de actualización.
5. **Plan de actualización por fases:**
   - Fase 0 (inmediata): parches de seguridad sin breaking changes.
   - Fase 1: actualizaciones menores.
   - Fase 2: actualizaciones mayores, con breaking changes conocidos y guía oficial
     de migración.
   - Fase 3: migración de runtime/framework si está en EOL.
   Cada fase con riesgo, esfuerzo estimado (bajo/medio/alto) y pruebas necesarias.
6. **Ítems A VERIFICAR**: todo lo que no pudiste confirmar.
7. **Anexo**: comandos ejecutados, cuáles fallaron y por qué.

Al terminar, muéstrame solo el resumen ejecutivo en el chat y la ruta del informe.
