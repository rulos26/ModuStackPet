<?php

namespace Database\Factories;

use App\Models\Raza;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Raza>
 */
class RazaFactory extends Factory
{
    protected $model = Raza::class;

    public function definition(): array
    {
        return [
            'tipo_mascota' => 'Perro',
            'nombre' => fake()->unique()->word(),
        ];
    }
}
