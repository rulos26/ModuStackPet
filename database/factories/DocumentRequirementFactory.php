<?php

namespace Database\Factories;

use App\Models\DocumentRequirement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<DocumentRequirement>
 */
class DocumentRequirementFactory extends Factory
{
    protected $model = DocumentRequirement::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'codigo' => strtoupper(Str::random(8)),
            'nombre' => ucfirst($name),
            'descripcion' => fake()->sentence(),
            'obligatorio' => true,
            'activo' => true,
            'orden' => fake()->numberBetween(1, 100),
            'tipo_validacion' => null,
            'dias_validez' => null,
            'formatos_permitidos' => ['pdf', 'jpg', 'jpeg', 'png'],
            'tamaño_maximo_kb' => 2048,
            'aplica_razas_peligrosas' => false,
        ];
    }
}
