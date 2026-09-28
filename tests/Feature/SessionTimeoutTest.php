<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * app/Http/Middleware/SessionTimeout.php estaba declarado en el grupo 'web'
 * de app/Http/Kernel.php, pero ese archivo es código muerto desde la
 * migración a la estructura de Laravel 11/12 sin Kernel.php: el middleware
 * nunca se ejecutaba y la expiración de sesión por inactividad estaba
 * completamente desactivada.
 */
class SessionTimeoutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function inactive_session_beyond_timeout_is_logged_out(): void
    {
        Role::create(['name' => 'Superadmin']);
        $user = User::factory()->create();
        $user->assignRole('Superadmin');

        // El timeout por defecto (sin registro en la tabla configuracions) es 1800s.
        $response = $this->actingAs($user)
            ->withSession(['last_activity' => time() - 2000])
            ->get(route('superadmin.dashboard'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    #[Test]
    public function active_session_within_timeout_is_not_logged_out(): void
    {
        Role::create(['name' => 'Superadmin']);
        $user = User::factory()->create();
        $user->assignRole('Superadmin');

        $response = $this->actingAs($user)
            ->withSession(['last_activity' => time() - 60])
            ->get(route('superadmin.dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->assertNotEquals(302, $response->getStatusCode());
    }
}
