<?php

namespace Database\Factories;

use App\Models\Module;
use App\Models\ModuleVerification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModuleVerification>
 */
class ModuleVerificationFactory extends Factory
{
    protected $model = ModuleVerification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'module_id' => Module::factory(),
            'verification_code' => $this->faker->numerify('######'),
            'action' => $this->faker->randomElement(['activate', 'deactivate']),
            'expires_at' => $this->faker->dateTimeBetween('now', '+1 hour'),
            'used_at' => null,
        ];
    }

    /**
     * Indicate that the verification is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => $this->faker->dateTimeBetween('-1 hour', '-1 minute'),
        ]);
    }

    /**
     * Indicate that the verification is used.
     */
    public function used(): static
    {
        return $this->state(fn (array $attributes) => [
            'used_at' => $this->faker->dateTimeBetween('-1 hour', 'now'),
        ]);
    }

    /**
     * Indicate that the verification is for activation.
     */
    public function forActivation(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'activate',
        ]);
    }

    /**
     * Indicate that the verification is for deactivation.
     */
    public function forDeactivation(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'deactivate',
        ]);
    }
}
