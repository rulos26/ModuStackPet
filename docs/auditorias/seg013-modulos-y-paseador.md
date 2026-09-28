# SEG-013: módulos fail-closed y tabla de Paseador

Fecha: 2026-09-28  
Agente: Codex  
Rama: `ia/codex/modulos-fail-closed-paseador`

## Parte A: `CheckModuleStatus` fail-closed

### Estado anterior y pruebas rojas

El middleware permitía continuar con `$next($request)` en dos situaciones en
las que no podía comprobar el estado del módulo:

- La tabla `modules` no existía.
- La consulta del módulo lanzaba cualquier `Throwable`.

Además, el contexto del segundo log incluía `$e->getMessage()`. Los mensajes de
excepciones de conexión pueden contener host, usuario u otros detalles que no
deben terminar en logs de aplicación.

No había una prueba previa que exigiera el comportamiento permisivo. Las cinco
pruebas existentes cubrían módulos activos, inactivos, usuarios no autenticados,
auditoría de denegaciones y auto-registro de módulos desconocidos. Ninguna tuvo
que reescribirse.

El commit rojo `e34eea0e` añadió dos pruebas al middleware:

1. Tabla `modules` ausente: debe devolver 503 y no invocar el siguiente paso.
2. Excepción al obtener la conexión: debe devolver 503, no invocar el siguiente
   paso y registrar el incidente sin incluir el detalle sensible simulado.

Ambas fallaron inicialmente porque recibieron 200. Las cinco pruebas anteriores
continuaron pasando.

### Corrección

El commit `98a230f1` aplica el criterio fail-closed:

- La comprobación de existencia de la tabla quedó dentro del `try`.
- Si falta `modules`, se registra un código de fallo genérico y se responde 503.
- Si la comprobación o consulta lanza una excepción, se registra solo la clase
  de excepción y el slug; no se registra el mensaje ni credenciales.
- La respuesta 503 usa un texto genérico y no expone información interna.
- En ningún caso de fallo se invoca `$next`.

Se conserva sin cambios funcionales el auto-registro de un slug desconocido con
`status=true`, decisión introducida en el commit `43b95d07`. La prueba
`middleware_auto_creates_and_allows_nonexistent_module` sigue pasando.

## Parte B: modelo `Paseador`

Laravel infería `paseadors` para la clase `Paseador`, pero la migración crea
`paseadores`. La nueva prueba intentó persistir una instancia y falló primero
con `no such table: paseadors`.

La corrección declara explícitamente:

```php
protected $table = 'paseadores';
```

La prueba ahora confirma tanto el nombre resuelto como la fila persistida en
`paseadores`.

## Revisión de otros modelos

Se compararon las clases de `app/Models` y sus propiedades `$table` con las
tablas creadas en `database/migrations`. No se encontró otro fallo confirmado
del mismo tipo:

- `Ciudad`, `Configuracion`, `Departamento`, `Empresa`, `OAuthProvider`,
  `PathDocumento`, `Sector` y `TipoEmpresa` ya fijan explícitamente sus tablas
  cuando la convención inglesa podría ser ambigua.
- Clases generadas como `Ciudade`, `Sectore`, `TiposEmpresa` y
  `VacunasCertificacione` resuelven por convención las tablas españolas
  existentes.

Sí existen pares que representan las mismas tablas (`Ciudad`/`Ciudade`,
`Sector`/`Sectore` y `TipoEmpresa`/`TiposEmpresa`). No causan el error revisado,
pero duplican conceptos y pueden divergir; conviene consolidarlos en una tarea
separada después de inventariar sus usos.

## Verificación

- Pruebas focales: 8 aprobadas, 17 aserciones.
- La suite completa y `composer validate` se registran en el Handoff de la tarea.

