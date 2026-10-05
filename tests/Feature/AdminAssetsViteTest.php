<?php

namespace Tests\Feature;

use App\Models\MensajeDeBienvenida;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * SEG-047: el layout AdminLTE carga Bootstrap/FA/SweetAlert2/AdminLTE/jQuery
 * vía Vite (public/build), no por CDN.
 */
class AdminAssetsViteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('Superadmin');
        MensajeDeBienvenida::create([
            'rol' => 'Superadmin',
            'titulo' => 'Bienvenido Superadmin',
            'descripcion' => 'Dashboard Superadmin',
            'logo' => 'storage/img/logo.jpg',
        ]);
    }

    public function test_dashboard_layout_uses_vite_build_not_cdn(): void
    {
        $this->assertFileExists(public_path('build/manifest.json'), 'Ejecuta npm run build antes de la suite.');

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $user->assignRole('Superadmin');

        $response = $this->actingAs($user)->get('/superadmin/dashboard');

        $response->assertOk();
        $response->assertDontSee('cdn.jsdelivr.net', false);
        $response->assertDontSee('cdnjs.cloudflare.com', false);
        $response->assertDontSee('code.jquery.com', false);
        $response->assertSee('/build/assets/', false);
        $response->assertSee('admin-', false);
    }
}
