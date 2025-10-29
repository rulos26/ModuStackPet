<?php

namespace Database\Factories;

use App\Models\Module;
use App\Models\ModuleLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModuleLog>
 */
class ModuleLogFactory extends Factory
{
    protected $model = ModuleLog::class;

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
            'action' => $this->faker->randomElement([
                'activated',
                'deactivated',
                'access_denied',
                'verification_sent',
                'verification_failed',
                'permission_changed',
            ]),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'timestamp' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /**
     * Indicate that the log is for access denied.
     */
    public function accessDenied(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'access_denied',
        ]);
    }

    /**
     * Indicate that the log is for module activation.
     */
    public function activated(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'activated',
        ]);
    }

    /**
     * Indicate that the log is for module deactivation.
     */
    public function deactivated(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'deactivated',
        ]);
    }
}
