# Informe unificado (SEG-023 + SEG-024) y propuesta de plan de trabajo

Fecha: 2026-09-28
Agente: Claude
Rama: `ia/claude/informe-unificado-plan`

Esta tarea **no ejecuta nada**: analiza, unifica y propone. El humano decide
después qué se convierte en tarea real. No se creó ningún archivo en
`docs/tareas/pendientes/` de las tareas propuestas más abajo, y no se tocó
código, dependencias ni `.env`.

## 0. Qué se leyó y qué se verificó de forma independiente

Se leyeron completos: `seg023-seguridad-post-l13.md`,
`seg024-consistencia-pendientes.md`, `seg001-correccion.md`,
`seg009-herramientas-web.md`, `seg010-escalada-y-kernel.md`,
`seg012-cifrado-credenciales.md`, `seg013-modulos-y-paseador.md`,
`seg014-superadmin-inicial.md`, `seg016-idor-mascotas.md`,
`seg017-documentos.md`, `seg018-tokenseeder-investigacion.md`,
`seg020-unificar-rutas-usuarios.md`, `seg021-kernel-y-modelos-duplicados.md`,
`seguridad-predespliegue.md`, `pendiente-rotacion-credenciales.md`,
`fase3-laravel13.md`, `verificacion-codex.md`, `diagnostico-pruebas.md`,
`auditoria-deps-cursor-local.md`, `pruebas-mascotas-documentos.md` y
`fase1-resultado.md`/`fase2-laravel12.md` (contexto de migraciones previas).

Además, antes de escribir conclusiones, se verificaron **directamente contra
el código actual** (no solo se repitió lo que dicen los informes) los puntos
que más pesan en la priorización:

| Verificación | Comando/lectura | Resultado |
|---|---|---|
| `config/cache.php` sigue sin `serializable_classes` | lectura del archivo | Confirmado: la clave no existe. |
| `CleanController` no aplica `EnsureAdminToolsEnabled` | lectura de `app/Http/Controllers/CleanController.php:13-17` | Confirmado: solo `$this->middleware('auth')`. La ruta sí vive dentro del grupo `superadmin` (`role:Superadmin`) y del gate de módulo `clean`, así que no es acceso abierto — es una capa de defensa en profundidad faltante, no una vulnerabilidad explotable por cualquiera. |
| `FortifyServiceProvider` de la app no registrado | `bootstrap/providers.php` | Confirmado: solo lista `AppServiceProvider` y `ViewServiceProvider`. |
| Controladores de auth duplicados, y cuál gana en cada ruta | `grep` de `routes/web.php` líneas 9-113 | Confirmado y precisado (ver §3.3): `RegisteredUserController` (Fortify/Breeze) queda **inalcanzable** porque `RegisterController` (raíz) registra la misma ruta `register` después y gana; `Auth\ResetPasswordController` está **inalcanzable** porque se usa la variante raíz; `app/Http/Controllers/LoginController.php` (raíz) está **inalcanzable** porque se usa `Auth\LoginController`. |
| `User` con `use HasRoles;` duplicado | `app/Models/User.php:17-19` | Confirmado: `use Notifiable; use HasRoles; use HasRoles;` (dos veces). |
| Sin migración `two_factor_*` ni trait en `User` | `grep -rln two_factor database/migrations/`, `grep TwoFactor app/Models/User.php` | Confirmado: cero coincidencias en ambos casos. |
| `AdminWebToolsTest` no cubre `clean`/AutoClean | `grep -i clean tests/Feature/AdminWebToolsTest.php` | Confirmado: cero coincidencias. |
| No hay `.github/workflows/` | `ls .github/workflows` | Confirmado: no existe. |

Estas verificaciones cambian una conclusión de SEG-023 (ver §4).

## 1. Resumen ejecutivo

Ninguna auditoría encontró una regresión crítica de seguridad introducida
por la migración a Laravel 13 en sí misma. Los controles de acceso ya
corregidos (SEG-001, SEG-010, SEG-016, SEG-013, SEG-012, SEG-009) siguen
intactos y probados. Lo que ambas auditorías exponen, cada una desde su
ángulo, es **deuda acumulada de antes de la migración** que la migración no
creó pero tampoco resolvió:

1. Una protección nueva de Laravel 13 (allowlist de clases cacheables) que
   el proyecto no adoptó, de aplicación trivial (SEG023-001).
2. Un hueco de defensa en profundidad preexistente en AutoClean
   (SEG023-002), heredado del mismo patrón que SEG-009/SEG-009b ya
   corrigieron en los otros 5 controladores administrativos.
3. Una característica de 2FA que **aparenta** estar activa en
   `config/fortify.php` pero que, verificado directamente, **no tiene
   soporte real** en el modelo `User` ni en el esquema de BD: si un usuario
   intentara usarla hoy, fallaría. Esto es más grave de lo que SEG-023 lo
   calificó (ver §4).
4. Dos stacks de autenticación superpuestos (Fortify/Breeze vs
   controladores propios heredados), con provider de la app sin registrar,
   una colisión de nombre de ruta y controladores completos que son código
   muerto inalcanzable.
5. Cero CI versionado y ~25 controladores/áreas sin prueba de feature
   dedicada, incluida toda la superficie de login social.
6. Tres pares de modelos duplicados (`Ciudad`/`Ciudade`,
   `Sector`/`Sectore`, `TipoEmpresa`/`TiposEmpresa`) que SEG-021 ya
   documentó en detalle y que ni SEG-023 ni SEG-024 encontraron
   consolidados todavía (SEG-024 lo reconfirma de forma independiente).
7. Dos pendientes que siguen **congelados por decisión humana explícita** y
   que este informe **no** reabre: rotación de credenciales
   (`pendiente-rotacion-credenciales.md`) y `TokenSeeder`
   (`seg018-tokenseeder-investigacion.md`).

Recomendación de por dónde empezar: **§7**.

## 2. Hallazgos que APARECEN EN AMBOS informes (coincidencias)

| Tema | SEG-023 | SEG-024 | Lectura unificada |
|---|---|---|---|
| Falta de pruebas de flujo para Socialite/Fortify tras el bump de dependencias | SEG023-003 (Baja: "riesgo de regresión no cubierta, no vulnerabilidad concreta") | C-011 (Alta, específico a Socialite) + C-012 (Alta, específico a 2FA) | **Coinciden en el síntoma** (sin tests de auth social/2FA) **pero difieren en severidad y en el diagnóstico de fondo**. Verificado independientemente: C-012 tiene razón en que 2FA no es solo "falta de tests", es una feature no implementada. Ver §4. |
| Deuda de consolidación de `Ciudad`/`Sector`/`TipoEmpresa` | No es tema de SEG-023 (fuera de su alcance post-L13) | C-004, reconfirmando SEG-021 | No es una coincidencia entre SEG-023/024 en sentido estricto, pero sí una **triple confirmación** (SEG-021 original de Codex + reconfirmación de Cursor en SEG-024 + no se tocó desde entonces): alta confianza en que sigue siendo un problema real y no una lectura desactualizada. |
| Ausencia de CI | Mencionado solo de forma indirecta (SEG-023 no lo lista como hallazgo, se centra en regresión de L13) | C-010 (Alta) | Coincide con `auditoria-deps-cursor-local.md` (DEP-014, ya lo señalaba en 2026-09-27): **tres auditorías independientes** en fechas distintas señalan la misma ausencia sin que se haya corregido. Máxima confianza. |

No hay más coincidencias directas de "mismo hallazgo, mismo ID" entre
SEG-023 y SEG-024: sus alcances son complementarios por diseño (SEG-023 =
regresión post-L13; SEG-024 = consistencia general + pendientes
congelados), no redundantes. Esto es esperable y no señala un problema del
proceso de auditoría.

## 3. Hallazgos que solo reportó UNO de los dos

### 3.1 Solo SEG-023 (regresión / endurecimiento post-L13)

| ID | Por qué SEG-024 no lo tocó |
|---|---|
| SEG023-001 (`cache.serializable_classes`) | Fuera del alcance de SEG-024 (consistencia general, no configuración de Laravel 13). No es una omisión: SEG-024 nunca reviió `config/cache.php`. |
| SEG023-002 (AutoClean sin `EnsureAdminToolsEnabled`) | Igual: SEG-024 no auditó los controladores de herramientas administrativas, se centró en modelos/auth/CI/tests. Verificado por mí de forma independiente (ver §0): confirmado, y es real aunque de severidad Media (ya está detrás de `role:Superadmin`). |
| SEG023-004 (passkeys instalado, inactivo) | Informativo; SEG-024 no menciona Fortify/passkeys en absoluto (su C-012 es sobre 2FA, no passkeys). No es un descuido: es simplemente un ángulo distinto. |
| SEG023-005 (phpseclib/Guzzle sin uso directo de APIs incompatibles) | Informativo, mismo motivo. |

### 3.2 Solo SEG-024 (consistencia general + pendientes)

| ID | Por qué SEG-023 no lo tocó |
|---|---|
| C-001 (rotación de credenciales sigue congelada) | Fuera del alcance de SEG-023 (regresión de L13). Es simplemente el estado de un pendiente ya documentado en `pendiente-rotacion-credenciales.md`; no es un hallazgo nuevo, es una confirmación de que sigue vigente. |
| C-002 (email real en `.env.example`) | SEG-023 no revisó `.env.example`. Hallazgo válido y de severidad baja. |
| C-003 (`TokenSeeder` sigue muerto/congelado) | Igual que C-001: confirmación de un pendiente ya decidido (SEG-018), no hallazgo nuevo. |
| C-004 (modelos duplicados) | Ver tabla de coincidencias arriba: en rigor es una reconfirmación de SEG-021, no un hallazgo nuevo de SEG-024, pero SEG-023 no lo tocó por estar fuera de su alcance de "regresión post-L13". |
| C-005 (auth duplicado + provider sin registrar) | SEG-023 no auditó `routes/web.php` para colisiones de nombre de ruta ni la estructura de controladores de auth; se centró en si el middleware de auth/CSRF seguía intacto (que sí). **Verificado independientemente por mí** (ver §0): confirmado con precisión de qué archivo gana en cada ruta. |
| C-006 (`aplicaParaRaza` siempre `true`) | Fuera del alcance de SEG-023 (no es un tema de Laravel 13 ni de seguridad de acceso). |
| C-007 (cosmético: nombres, import muerto, trait duplicado) | Igual, fuera de alcance de SEG-023. Verificado: el trait duplicado en `User.php` es real. |
| C-008 (controladores de auth muertos) | Relacionado con C-005; ver §3.3 para la lista exacta y precisa de qué está realmente muerto. |
| C-009 (~25 controladores sin Feature test) | SEG-023 solo revisó la cobertura de las áreas que él mismo señaló como sensibles (auth/2FA), no hizo un inventario completo de toda la aplicación como sí hizo SEG-024. |

### 3.3 Precisión adicional que ninguno de los dos informes dio (verificada por mí)

Ambos informes dicen "hay controladores de auth duplicados" pero ninguno
dice **cuál variante es la que realmente se ejecuta**. Verificado con
`grep` sobre `routes/web.php`:

| Función | Variante viva (la que registra la ruta que gana) | Variante muerta (inalcanzable) |
|---|---|---|
| Registro | `App\Http\Controllers\RegisterController` (raíz) — se registra **después** de `RegisteredUserController` con el mismo nombre `register`, así que gana por el mismo mecanismo de sobrescritura que SEG-001 documentó para `superadmin.dashboard` | `App\Http\Controllers\RegisteredUserController` (Fortify/Breeze) |
| Login | `App\Http\Controllers\Auth\LoginController` | `App\Http\Controllers\LoginController` (raíz) |
| Reset de contraseña | `App\Http\Controllers\ResetPasswordController` (raíz) | `App\Http\Controllers\Auth\ResetPasswordController` |

Esto importa para dimensionar el esfuerzo de C-005/C-008: no hace falta
"decidir" cuál controlador de login/registro/reset usar como si estuvieran
empatados — el tráfico real ya pasa por uno específico de cada par. La
decisión de producto real es **cuál stack de alto nivel** (Fortify con sus
acciones/vistas vs. los controladores propios heredados) se declara
oficialmente soportado, porque eso determina si `FortifyServiceProvider` se
registra y se completa (para 2FA) o se retira junto con el resto de la
infraestructura Fortify no usada.

## 4. Contradicción resuelta: severidad de "Fortify 2FA"

- SEG-023 (SEG023-003) trata esto como informativo/bajo: dice que "Fortify
  1.40 conserva 2FA activo con confirmación y confirmación de contraseña" y
  solo señala que falta cobertura de test para esa función y para
  Socialite, sin encontrar "una vulnerabilidad concreta en el código". Su
  única evidencia fue `php artisan route:list` (las rutas de 2FA de
  Fortify aparecen registradas).
- SEG-024 (C-012) va más profundo: revisa `app/Models/User.php` (sin trait
  `TwoFactorAuthenticatable`), busca migraciones con columnas
  `two_factor_*` (no existen) y nota que `FortifyServiceProvider` de la app
  no está registrado. Concluye que 2FA "está declarado en config pero no
  implementado en el modelo/esquema" y lo marca **Alta**.

**Verificación independiente (§0) confirma la lectura de SEG-024, no la de
SEG-023**: no existe ninguna migración con columnas `two_factor_*` en
`database/migrations/`, y `app/Models/User.php` no usa el trait
`TwoFactorAuthenticatable` de Fortify en ningún punto. Que las rutas
aparezcan en `route:list` solo prueba que Fortify las registra porque la
característica está en el arreglo `'features'` de `config/fortify.php`; no
prueba que la ruta funcione, porque Fortify llama a métodos de ese trait
(`twoFactorAuthSecret()`, `two_factor_recovery_codes`, etc.) que no existen
en el modelo actual. Cualquier usuario que hoy intente activar 2FA muy
probablemente encontraría un error (columna/atributo inexistente), no una
funcionalidad real.

**Conclusión para el plan**: este hallazgo se trata como **Alta** (severidad
de SEG-024), no Baja. No es "faltan pruebas" — es "hay una funcionalidad de
seguridad que la configuración anuncia como disponible pero que no
funciona", lo cual es peor que no ofrecerla en absoluto (genera una falsa
sensación de que las cuentas tienen 2FA disponible).

No se encontraron otras contradicciones directas entre los dos informes.

## 5. Hallazgos previos de `seguridad-predespliegue.md` que NINGUNA de las dos auditorías nuevas re-verificó

Esto es un meta-hallazgo de esta tarea, no de SEG-023 ni SEG-024: al leer
`seguridad-predespliegue.md` (la auditoría original, con hallazgos SEG-002,
SEG-003, SEG-006, SEG-008, SEG-009, SEG-010 de severidad Crítica/Alta/Media)
se nota que **ninguna auditoría posterior confirmó si esos hallazgos siguen
vigentes** más allá de lo que ya corrigió la tarea SEG-009 (deshabilitar las
herramientas administrativas web fuera de `local`):

- SEG-002 (backup a destino MySQL arbitrario, posible exfiltración) y SEG-003
  (reescritura de `.env` desde HTTP) y SEG-008 (inyección en el valor
  escrito a `.env` vía regex) — la causa raíz **no se corrigió**, solo se le
  puso una puerta (`admin_tools.enabled` = `false` fuera de `local`) delante.
  Si esa puerta llegara a reactivarse alguna vez por una necesidad
  operativa real, las vulnerabilidades subyacentes seguirían ahí, sin que
  ninguna auditoría desde entonces las haya vuelto a mirar.
- SEG-006 (document root / `.htaccess`) y SEG-010 (retención de PII en
  `oauth_test_logs`) tampoco aparecen re-verificados en SEG-023 ni SEG-024.

No se trata como una tarea urgente en este informe porque el "candado"
(`admin_tools.enabled`) sigue cerrado y probado (`AdminWebToolsTest`), y
reabrirlo no está en el radar de nadie ahora mismo. Pero se deja explícito
para que el humano decida si vale la pena una auditoría de seguimiento
dedicada exclusivamente a re-verificar esos 6 hallazgos originales antes de
cualquier despliegue real, en vez de asumir que "quedaron resueltos" solo
porque nadie los ha vuelto a mencionar.

## 6. Tabla de hallazgos unificados: severidad, esfuerzo, dependencias

Los IDs `U-xxx` son de este informe (unificados); se mapean a los IDs
originales entre paréntesis.

| ID unificado | Hallazgo | Severidad | Esfuerzo | Depende de |
|---|---|---|---|---|
| U-01 (SEG023-001) | `cache.serializable_classes` no adoptado | Media | Bajo | Ninguno |
| U-02 (SEG023-002) | AutoClean sin `EnsureAdminToolsEnabled` | Media | Bajo | Ninguno |
| U-03 (C-010) | Sin CI (`.github/workflows`) | Alta | Bajo | Ninguno |
| U-04 (C-002) | Email real en `.env.example` | Baja | Bajo | Ninguno |
| U-05 (C-007, parcial) | Trait `HasRoles` duplicado en `User.php` | Baja | Bajo | Ninguno |
| U-06 (C-005 + C-008) | Auth duplicado: colisión de ruta `register`, `FortifyServiceProvider` sin registrar, controladores muertos identificados en §3.3 | Alta | Medio (decisión) + Bajo (ejecución una vez decidido) | **Requiere decisión humana previa**: ¿stack oficial = Fortify o controladores propios? |
| U-07 (C-012, reclasificado — ver §4) | 2FA "activo" en config pero sin trait/migración/provider | Alta | Medio–Alto | U-06 (si el stack oficial es Fortify, hace falta el provider registrado antes de completar 2FA; si no, la decisión es apagar la feature) |
| U-08 (C-011, SEG023-003) | Socialite sin Feature tests tras bump mayor | Alta | Medio | Ninguno (puede ir en paralelo con U-06/U-07: toca `tests/`, no los controladores de auth de contraseña) |
| U-09 (C-009) | ~25 controladores/áreas sin Feature test | Alta | Alto (se recomienda trocear, ver §7) | Ninguno en general; los sub-lotes de auth dependen de U-06 |
| U-10 (C-004, SEG-021) | 3 pares de modelos duplicados sin consolidar | Media | Medio–Alto (con pruebas de caracterización) | Ninguno técnico, pero **no paralelizable entre sí** (ver §6.1) |
| U-11 (C-003, SEG-018) | `TokenSeeder` código muerto | Media | — | **Congelado por decisión humana** hasta versión 3.0. No proponer tarea todavía. |
| U-12 (C-001, rotación) | Rotación de credenciales pendiente | Alta (cuando se retome) | — | **Congelado por decisión humana** hasta rediseñar login social. No proponer tarea todavía. |
| U-13 (C-006) | `aplicaParaRaza` no filtra razas peligrosas | Media | Bajo–Medio | Ninguno |
| U-14 (§5, meta-hallazgo) | SEG-002/003/006/008/009/010 de `seguridad-predespliegue.md` sin re-verificar desde que se cerró el acceso vía `admin_tools` | Informativo (riesgo latente, no activo) | — | Ninguno; se propone como auditoría de solo lectura, no como corrección |

### 6.1 Nota de paralelismo para U-10

Los tres pares comparten al menos un archivo consumidor
(`EmpresaController.php` usa `Ciudad`, `Sector` y `TipoEmpresa` a la vez;
`Departamento::ciudades()` toca el par `Ciudad`/`Ciudade`). Aunque cada par
vive en su propio modelo/controlador/vista específico, tocar los tres a la
vez en ramas paralelas arriesga conflictos de merge en `EmpresaController.php`
y sus vistas. Se recomienda **secuencial**, no paralelo, una sola rama a la
vez, aunque cada consolidación (Ciudad, luego Sector, luego TipoEmpresa)
pueda ser una tarea independiente con su propio PR.

## 7. Plan de tareas propuesto

Numeración sugerida a partir de 026 (siguiente libre tras esta tarea 025).
**No se crearon estos archivos**; es una propuesta para que el humano decida.

| # propuesto | Título | Agente sugerido | Archivos que tocaría | Depende de | Se puede paralelizar con |
|---|---|---|---|---|---|
| 026 | Adoptar `cache.serializable_classes = false` | **Cursor** (bajo riesgo, fácil de verificar: agregar una clave, correr la suite) | `config/cache.php` | Ninguna | 027, 028, 029, 032 |
| 027 | Aplicar `EnsureAdminToolsEnabled` a `CleanController` + cubrirlo en `AdminWebToolsTest` | Codex (mismo patrón que ya aplicó en SEG-009/013) | `app/Http/Controllers/CleanController.php`, `tests/Feature/AdminWebToolsTest.php` | Ninguna | 026, 028, 029, 032 |
| 028 | Añadir workflow mínimo de GitHub Actions (PHP 8.3 + `composer install` + `php artisan test` + `composer validate`) | **Cursor** (bajo riesgo: un archivo YAML nuevo, se verifica localmente reproduciendo los mismos comandos) | `.github/workflows/tests.yml` (nuevo) | Ninguna | 026, 027, 029, 032 |
| 029 | Reemplazar email real por placeholder en `.env.example` + eliminar `use HasRoles;` duplicado en `User.php` | **Cursor** (dos cambios triviales de una línea, sin lógica) | `.env.example`, `app/Models/User.php` | Ninguna | 026, 027, 028, 032 |
| 030 | **Decisión humana, no tarea de agente todavía**: elegir stack de auth oficial (Fortify vs controladores propios) | — (humano) | — | Ninguna | — |
| 031 | Resolver duplicación de auth: registrar/retirar `FortifyServiceProvider`, eliminar la colisión de ruta `register`, mover a `_borrar/` los 3 controladores muertos identificados en §3.3 | Claude (cambio arquitectónico, requiere el protocolo de verificación completo) | `bootstrap/providers.php`, `routes/web.php`, `app/Http/Controllers/RegisteredUserController.php` o `RegisterController.php` (uno de los dos, según 030), `app/Http/Controllers/LoginController.php` o `Auth/LoginController.php`, `app/Http/Controllers/ResetPasswordController.php` o `Auth/ResetPasswordController.php`, `_borrar/` | **030** | Nada de auth (032, 033) hasta que esto termine |
| 032 | Implementar 2FA completo (trait + migración + vistas ya existen en Fortify) **o** desactivar `Features::twoFactorAuthentication()` en config hasta implementarlo — decisión incluida en el alcance de la tarea, con recomendación pero sin imponerla | Claude | `config/fortify.php`, `app/Models/User.php`, migración nueva, `tests/Feature/TwoFactorAuthTest.php` (nuevo) | **031** (necesita el provider ya resuelto) | 026, 027, 028, 029 antes de empezar; no paralelizable con 033 (ambos tocan flujos de auth y podrían pisar fixtures de test compartidas) |
| 033 | Feature tests de Socialite (redirect, callback usuario nuevo/existente, proveedor inactivo, error del proveedor) con mocks de `Socialite::shouldReceive` | Codex | `tests/Feature/SocialAuthTest.php` (nuevo) | Ninguna técnica, pero se recomienda **después de 031** para no duplicar esfuerzo de entender el árbol de auth | 026-029; no en paralelo con 032 (mismo motivo) |
| 034 | Consolidar `Ciudad`/`Ciudade` (pruebas de caracterización primero, luego migrar consumidores) | Claude | `app/Models/Ciudad.php`, `app/Models/Ciudade.php`, `app/Http/Controllers/CiudadController.php`, `app/Models/Departamento.php`, vistas de ciudad, `tests/` | Ninguna | No con 035/036 (mismo archivo `EmpresaController` en juego, ver §6.1) |
| 035 | Consolidar `Sector`/`Sectore` | Claude | `app/Models/Sector.php`, `app/Models/Sectore.php`, `app/Http/Controllers/SectoreController.php`, vistas de sector, `tests/` | **034** (secuencial, no técnico) | No con 034/036 |
| 036 | Consolidar `TipoEmpresa`/`TiposEmpresa` | Claude | `app/Models/TipoEmpresa.php`, `app/Models/TiposEmpresa.php`, `app/Http/Controllers/TiposEmpresaController.php`, vistas, `tests/` | **035** (secuencial, no técnico) | No con 034/035 |
| 037 | Implementar `aplicaParaRaza` (razas peligrosas) o documentar explícitamente que el flag no tiene efecto todavía | Codex o Claude, indistinto | `app/Models/DocumentRequirement.php`, `tests/` | Ninguna | Todo lo demás |
| 038 | Feature tests para el primer lote de controladores sin cobertura (catálogos: `CiudadController`/`SectoreController`/`TiposEmpresaController` ya quedan cubiertos por 034-036; priorizar `ClienteController`, `PaseadorController`, `AdminController`, `EmpresaController`) | Codex | `tests/Feature/` (nuevos) | Se beneficia de que 034-036 ya hayan estabilizado esos modelos, pero no es estrictamente necesario | Puede ir en paralelo con 037 |
| — | Auditoría de solo lectura para re-verificar SEG-002/003/006/008/009/010 de `seguridad-predespliegue.md` (U-14) | Cursor o Codex (solo lectura, bajo riesgo) | `docs/auditorias/` únicamente | Ninguna | Con casi todo lo demás (es de solo lectura) |

No se propone ninguna tarea para U-11 (TokenSeeder) ni U-12 (rotación de
credenciales): ambas siguen congeladas por decisión humana explícita y esta
tarea no las reabre.

## 8. Recomendación de por dónde empezar

1. **Primero, en paralelo, las 4 tareas de bajo riesgo y esfuerzo** (026,
   027, 028, 029): no tienen dependencias entre sí, tocan archivos
   distintos, y una de ellas (028, CI) empieza a dar red de seguridad
   automática para todo lo que venga después. Son ideales para repartir
   entre Cursor (026, 028, 029, que además son fáciles de verificar
   manualmente sin el protocolo de `scripts/verificar-rama.sh`) y Codex
   (027, que sigue un patrón ya establecido en SEG-009/013).
2. **Después, la decisión humana 030** (qué stack de auth es el oficial):
   es un bloqueador real para 031, 032 y en menor medida 033, así que
   conviene resolverla temprano en vez de dejarla para el final.
3. **031 antes que 032/033**: no tiene sentido implementar 2FA o probar
   Socialite sobre una base de auth que todavía tiene una ruta duplicada y
   un provider sin registrar.
4. **034-036 (consolidación de modelos) pueden avanzar en paralelo con
   todo el bloque de auth** (030-033), porque no comparten archivos; sí
   deben ir secuenciales entre sí (§6.1).
5. **037 y 038 al final**, ya que ninguno es bloqueante para nada más y se
   benefician de que el resto del árbol esté más estable.
6. La auditoría de solo lectura de U-14 puede lanzarse en cualquier
   momento, incluso ahora mismo, porque no interfiere con nada.

Si hubiera que elegir **una sola tarea para empezar hoy**: **028 (CI
mínimo)**, porque es la que más protege todo el trabajo posterior — a
partir de ese momento, cualquier regresión en las tareas 029-038 se
detecta automáticamente en cada PR en vez de depender de que cada agente
recuerde correr `php artisan test` manualmente.


## 9. Confirmación U-01 (tarea 026) — `cache.serializable_classes = false`

Fecha: 2026-09-28. Agente: Cursor. Rama: `ia/cursor/cache-serializable-classes`.

### Revisión previa de usos de caché en `app/`

No se cachean objetos PHP completos. Los únicos `Cache::` relevantes:

- `Configuracion::obtenerValor` (`app/Models/Configuracion.php:34`):
  `Cache::remember` de un valor escalar (`valor` o default).
- `ModulesSyncCommand` (`app/Console/Commands/ModulesSyncCommand.php:46-47`):
  `Cache::put` de arrays de strings (`pluck('slug')->toArray()`).
- Limpieza: `Cache::forget` / `Cache::flush` en `Configuracion` y
  `ConfiguracionController`.

Coherente con lo ya auditado en `docs/auditorias/fase3-laravel13.md`.
Ningún bloqueante: se aplicó el cambio.

### Cambio aplicado

En `config/cache.php`, al final del array de retorno (después de
`prefix`), se añadió la clave y el bloque de documentación del skeleton
oficial de Laravel 13
(https://github.com/laravel/laravel/blob/13.x/config/cache.php):

```php
'serializable_classes' => false,
```

No se tocó `.env` ni dependencias.

### Verificación

- `php artisan test`: **144 passed** (436 assertions).
- `composer validate --no-check-publish`: **valid**.


## 10. Confirmación U-03 (tarea 028) — workflow mínimo de GitHub Actions

Fecha: 2026-10-03. Agente: Cursor. Rama: `ia/cursor/ci-github-actions`.

### Workflow creado

Archivo nuevo: `.github/workflows/tests.yml`.

- Disparadores: `push` y `pull_request` a `main`.
- Runner: `ubuntu-latest`.
- PHP: `shivammathur/setup-php@v2` con PHP **8.3** (`composer.json` exige `^8.3`).
- Extensiones: `mbstring, dom, fileinfo, pdo_sqlite, sqlite3, curl, zip, gd, bcmath, tokenizer, xml, ctype, openssl`
  (SQLite en memoria para PHPUnit; `gd`/`dom` por `barryvdh/laravel-dompdf`).
- Checkout: `actions/checkout@v4`.
- Sin secrets ni credenciales.
- No copia `.env` ni `.env.testing`: `phpunit.xml` ya define `APP_KEY`,
  `DB_CONNECTION=sqlite` y `DB_DATABASE=:memory:`.

Pasos del job (comandos exactos):

1. `composer install --no-interaction`
2. `php artisan test`
3. `composer validate`

### Verificación local (mismos comandos)

Ejecutados en el worktree Cursor el 2026-10-03:

- `composer install --no-interaction`: OK (exit 0).
- `php artisan test`: **146 passed** (438 assertions).
- `composer validate`: `./composer.json is valid`.