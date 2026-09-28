---
agente: codex
estado: en-curso
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
