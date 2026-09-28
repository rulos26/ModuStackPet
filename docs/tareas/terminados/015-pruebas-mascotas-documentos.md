---
agente: codex
estado: terminado
rama: ia/codex/pruebas-mascotas-documentos
archivos: [tests/Feature/, database/factories/, docs/]
---

# Pruebas de los flujos de mascotas y documentos

Hoy las pruebas cubren seguridad y módulos, pero no el núcleo de la aplicación.
Antes de publicar (y antes de migrar a Laravel 13) hacen falta pruebas de los
flujos principales.

## Qué hacer
1. Revisa rutas, controladores y vistas de mascotas y documentos de mascotas
   (subir, aprobar, rechazar, descargar).
2. Crea las factories que falten para esos modelos.
3. Escribe pruebas de:
   - Caminos felices: crear, ver, editar y listar mascotas; subir un documento
     (usa Storage::fake); aprobar, rechazar y descargar.
   - Autorización: un Cliente no ve ni modifica mascotas ni documentos de
     otro Cliente; quién puede aprobar y rechazar.
   - Validación: archivos no permitidos o demasiado grandes.
4. NO modifiques código de `app/`. Si una prueba revela un error, NO la dejes
   fallando en la suite: documenta el error (con la prueba que lo reproduce)
   en el entregable.

## Entregable
`docs/auditorias/pruebas-mascotas-documentos.md`: qué se cubre, errores
encontrados y su gravedad.

## Restricciones
- Solo `tests/`, `database/factories/` y `docs/`. No toques `.env` ni dependencias.
- Todas las pruebas (118 y las nuevas) deben pasar.
- Al terminar, vuelve con `git switch --detach origin/main`.

## Handoff
- Agente y fecha: Codex, 2026-09-28.
- Qué se hizo: se añadieron factories para Raza, Mascota, DocumentRequirement y MascotaDocument, y 9 pruebas de flujos de mascotas/documentos con 58 aserciones. Cubren CRUD feliz de mascotas propias, filtrado del listado, carga y descarga con Storage fake, aprobación/rechazo, autorización sobre documentos y validación de tipo/tamaño. Se documentaron cuatro defectos sin tocar `app/`.
- Archivos modificados: `database/factories/RazaFactory.php`, `database/factories/MascotaFactory.php`, `database/factories/DocumentRequirementFactory.php`, `database/factories/MascotaDocumentFactory.php`, `tests/Feature/MascotaFlowsTest.php`, `tests/Feature/MascotaDocumentFlowsTest.php`, `docs/auditorias/pruebas-mascotas-documentos.md` y esta ficha.
- Cómo probarlo: `php artisan test` (127 pruebas, 330 aserciones, todas pasan); `composer validate` (`composer.json` válido). Prueba focal: `php artisan test tests/Feature/MascotaFlowsTest.php tests/Feature/MascotaDocumentFlowsTest.php` (9 pruebas, 58 aserciones).
- Pendientes y riesgos: crítico: IDOR y falta de autenticación en el CRUD de mascotas. Alto: un Cliente autoaprueba documentos válidos al subirlos; `MascotaDocumentController@update` deja una transacción abierta al negar un documento ajeno. Medio: los controladores exponen mensajes internos de excepciones. Las reproducciones y recomendaciones están en el entregable.
- Preguntas para el humano: ¿se prioriza una tarea de corrección para el IDOR de mascotas y otra para separar validación automática de aprobación administrativa? La primera debería tratarse como bloqueo de publicación.
