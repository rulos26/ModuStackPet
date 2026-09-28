<?php

namespace Database\Factories;

use App\Models\Mascota;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mascota>
 */
class MascotaFactory extends Factory
{
    protected $model = Mascota::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nombre' => fake()->unique()->firstName(),
            'edad' => fake()->numberBetween(0, 18),
            'raza_id' => RazaFactory::new(),
            'genero' => fake()->randomElement(['Macho', 'Hembra']),
            'vacunas_completas' => fake()->boolean(),
            'comportamiento' => fake()->sentence(),
            'recomendaciones' => fake()->sentence(),
            'esterilizado' => fake()->boolean(),
            'enfermedades' => null,
        ];
    }
}
