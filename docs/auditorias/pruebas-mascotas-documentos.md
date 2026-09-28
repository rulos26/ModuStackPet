# Pruebas de mascotas y documentos de mascotas

Fecha: 2026-09-28  
Agente: Codex  
Rama: `ia/codex/pruebas-mascotas-documentos`

## Cobertura añadida

Se añadieron cuatro factories:

- `RazaFactory`
- `MascotaFactory`
- `DocumentRequirementFactory`
- `MascotaDocumentFactory`

Las pruebas de `MascotaFlowsTest` cubren:

- Crear una mascota y asignarla al usuario autenticado.
- Listar solo las mascotas propias para un Cliente.
- Ver y editar una mascota propia.
- Validación de los campos obligatorios.

Las pruebas de `MascotaDocumentFlowsTest` cubren:

- Subir un PDF válido para una mascota propia usando `Storage::fake('public')`.
- Descargar el archivo existente como propietario.
- Aprobar y rechazar como Admin, y aprobar como Superadmin.
- Rechazar a un Cliente que intenta subir, ver, modificar, descargar, aprobar o
  rechazar documentos de la mascota de otro Cliente.
- Rechazar extensiones no permitidas y archivos que superan el límite del
  requisito, sin crear filas ni archivos.

Resultado focal: **9 pruebas y 58 aserciones aprobadas**.

## Errores encontrados

### 1. IDOR completo en el CRUD de mascotas — crítica

Las rutas `mascotas.*` solo tienen `CheckModuleStatus`; no exigen `auth` ni
`verified`. Además, `MascotaController@show`, `edit`, `update` y `destroy` no
comprueban el propietario ni un rol administrativo.

Impacto:

- Un invitado puede alcanzar `show`, `edit` y `destroy`; las acciones que no
  consultan `auth()->user()` pueden exponer o borrar una mascota conociendo su
  id.
- Un Cliente puede ver, editar o borrar mascotas de otro Cliente.
- `update` fuerza `user_id` al usuario que hace la petición, por lo que además
  puede apropiarse de la mascota ajena.
- El filtrado correcto de `index` no mitiga el acceso directo por id.

Prueba de reproducción que debe añadirse al corregir `app/` (hoy falla porque
recibe una respuesta exitosa o redirección en vez de 403):

```php
$owner = $this->userWithRole('Cliente');
$attacker = $this->userWithRole('Cliente');
$pet = MascotaFactory::new()->create(['user_id' => $owner->id]);

$this->actingAs($attacker)
    ->get(route('mascotas.show', $pet))
    ->assertForbidden();

$this->actingAs($attacker)
    ->put(route('mascotas.update', $pet), $validData)
    ->assertForbidden();

$this->assertDatabaseHas('mascotas', [
    'id' => $pet->id,
    'user_id' => $owner->id,
]);
```

Recomendación: añadir `auth` y `verified` al grupo de rutas y aplicar una policy
de `Mascota` en todas las acciones de objeto, incluidos `destroy` y cualquier
flujo relacionado.

### 2. Un Cliente autoaprueba documentos al subirlos — alta

`MascotaDocumentController@store` inicializa el estado como `pendiente`, pero lo
cambia inmediatamente a `aprobado` cuando la validación automática es válida y
registra al propio usuario que subió el archivo como `usuario_aprobo_id`. Esto
elude la restricción explícita de los endpoints `aprobar` y `rechazar`, que sí
exigen Admin o Superadmin.

Reproducción:

```php
$this->actingAs($cliente)->post(route('mascota-documents.store'), $payload);

$this->assertDatabaseHas('mascota_documents', [
    'usuario_subio_id' => $cliente->id,
    'usuario_aprobo_id' => $cliente->id,
    'estado' => 'aprobado',
]);
```

Recomendación: separar “validación automática superada” de “aprobación humana”;
el documento debería conservar un estado pendiente hasta la decisión de un rol
autorizado.

### 3. Transacción abierta al negar actualización ajena — alta

`MascotaDocumentController@update` ejecuta `DB::beginTransaction()` antes de
verificar propiedad. Si el documento es ajeno, retorna una redirección sin
`commit` ni `rollBack`. La prueba reprodujo el efecto: las pruebas siguientes
fallaron con `There is already an active transaction`.

La prueba de autorización conserva la comprobación de que la BD no cambia y
ejecuta un `DB::rollBack()` compensatorio, con comentario explícito, para que el
defecto de producción no contamine la suite.

Recomendación: autorizar antes de iniciar la transacción o lanzar una excepción
de autorización; toda salida posterior a `beginTransaction` debe cerrar la
transacción.

### 4. Errores internos expuestos al usuario — media

Los `catch` de ambos controladores concatenan `$e->getMessage()` en mensajes de
sesión. Una excepción de almacenamiento o BD puede revelar rutas, nombres de
tabla o detalles de infraestructura. Debe registrarse internamente un contexto
sanitizado y mostrarse un mensaje genérico.

## Alcance respetado

No se modificó código de `app/`, rutas, dependencias ni `.env`. Los defectos que
requieren cambios de producción se documentaron y no se convirtieron en
expectativas inseguras dentro de la suite.

