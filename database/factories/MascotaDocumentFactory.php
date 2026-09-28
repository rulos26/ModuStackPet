<?php

namespace Database\Factories;

use App\Models\DocumentRequirement;
use App\Models\MascotaDocument;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MascotaDocument>
 */
class MascotaDocumentFactory extends Factory
{
    protected $model = MascotaDocument::class;

    public function definition(): array
    {
        return [
            'mascota_id' => MascotaFactory::new(),
            'document_requirement_id' => DocumentRequirement::factory(),
            'nombre_archivo' => 'documento.pdf',
            'ruta_archivo' => 'documentos_mascotas/'.fake()->uuid().'/documento.pdf',
            'tipo_mime' => 'application/pdf',
            'tamaño_bytes' => 1024,
            'hash_archivo' => hash('sha256', fake()->uuid()),
            'estado' => 'pendiente',
            'validacion_automatica' => false,
            'detalles_validacion' => [],
            'usuario_subio_id' => User::factory(),
        ];
    }
}
