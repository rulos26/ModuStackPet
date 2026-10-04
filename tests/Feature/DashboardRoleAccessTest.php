<?php

namespace Tests\Feature;

use App\Models\MensajeDeBienvenida;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * SEG-043c: cada dashboard de rol exige auth + verified + su propio role.
 */
class DashboardRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['Superadmin', 'Admin', 'Cliente', 'Paseador'] as $role) {
            Role::create(['name' => $role]);
            MensajeDeBienvenida::create([
                'rol' => $role,
                'titulo' => "Bienvenido {$role}",
                'descripcion' => "Dashboard {$role}",
                'logo' => 'storage/img/logo.jpg',
            ]);
        }
    }

    private function userWithRole(string $role, array $attrs = []): User
    {
        $user = User::factory()->create($attrs);
        $user->assignRole($role);

        return $user;
    }

    public static function ownDashboardProvider(): array
    {
        return [
            'Superadmin' => ['Superadmin', '/superadmin/dashboard', 'superadmin.dashboard'],
            'Admin' => ['Admin', '/admin/dashboard', 'admin.dashboard'],
            'Cliente' => ['Cliente', '/cliente/dashboard', 'cliente.dashboard'],
            'Paseador' => ['Paseador', '/paseador/dashboard', 'paseador.dashboard'],
        ];
    }

    public static function forbiddenDashboardProvider(): array
    {
        return [
            'Cliente → admin' => ['Cliente', '/admin/dashboard'],
            'Cliente → paseador' => ['Cliente', '/paseador/dashboard'],
            'Cliente → superadmin' => ['Cliente', '/superadmin/dashboard'],
            'Paseador → admin' => ['Paseador', '/admin/dashboard'],
            'Paseador → cliente' => ['Paseador', '/cliente/dashboard'],
            'Paseador → superadmin' => ['Paseador', '/superadmin/dashboard'],
            'Admin → cliente' => ['Admin', '/cliente/dashboard'],
            'Admin → paseador' => ['Admin', '/paseador/dashboard'],
            'Admin → superadmin' => ['Admin', '/superadmin/dashboard'],
            'Superadmin → admin' => ['Superadmin', '/admin/dashboard'],
            'Superadmin → cliente' => ['Superadmin', '/cliente/dashboard'],
            'Superadmin → paseador' => ['Superadmin', '/paseador/dashboard'],
        ];
    }

    #[Test]
    #[DataProvider('forbiddenDashboardProvider')]
    public function wrong_role_cannot_open_another_roles_dashboard(string $role, string $uri): void
    {
        $this->actingAs($this->userWithRole($role))
            ->get($uri)
            ->assertForbidden();
    }

    #[Test]
    #[DataProvider('ownDashboardProvider')]
    public function each_role_can_open_only_its_own_dashboard(string $role, string $uri, string $routeName): void
    {
        $response = $this->actingAs($this->userWithRole($role))->get($uri);

        $this->assertNotSame(403, $response->getStatusCode(), "{$role} recibió 403 en su dashboard");
        $this->assertTrue($response->isSuccessful() || $response->isRedirect());
    }

    #[Test]
    public function dashboards_require_verified_email(): void
    {
        foreach ([
            ['Admin', '/admin/dashboard'],
            ['Cliente', '/cliente/dashboard'],
            ['Paseador', '/paseador/dashboard'],
            ['Superadmin', '/superadmin/dashboard'],
        ] as [$role, $uri]) {
            $user = User::factory()->unverified()->create();
            $user->assignRole($role);

            $this->actingAs($user)->get($uri)->assertRedirect(route('verification.notice'));
        }
    }

    #[Test]
    public function dashboard_routes_have_auth_verified_and_matching_role(): void
    {
        $expected = [
            'superadmin.dashboard' => 'role:Superadmin',
            'admin.dashboard' => 'role:Admin',
            'cliente.dashboard' => 'role:Cliente',
            'paseador.dashboard' => 'role:Paseador',
        ];

        foreach ($expected as $name => $roleMiddleware) {
            $route = Route::getRoutes()->getByName($name);
            $this->assertNotNull($route, "Falta {$name}");
            $middleware = $route->gatherMiddleware();
            $this->assertContains('auth', $middleware, "{$name} sin auth");
            $this->assertContains('verified', $middleware, "{$name} sin verified");
            $this->assertContains($roleMiddleware, $middleware, "{$name} sin {$roleMiddleware}");
        }
    }

    #[Test]
    #[DataProvider('ownDashboardProvider')]
    public function login_still_redirects_each_role_to_its_dashboard(string $role, string $uri, string $routeName): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret-pass-1')]);
        $user->assignRole($role);

        $this->post('/login', ['email' => $user->email, 'password' => 'secret-pass-1'])
            ->assertRedirect(route($routeName));
    }
}
