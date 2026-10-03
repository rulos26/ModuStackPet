<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * SEG-043: hardening de hallazgos P1/P2 de seg039b
 * (notificaciones/leidas + dashboards duplicados).
 */
class Seg039bRoutesHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['Superadmin', 'Admin', 'Cliente', 'Paseador'] as $role) {
            Role::create(['name' => $role]);
        }
    }

    private function userWithRole(string $role, array $attrs = []): User
    {
        $user = User::factory()->create($attrs);
        $user->assignRole($role);

        return $user;
    }

    private function createUnreadNotification(User $user, string $message): DatabaseNotification
    {
        return DatabaseNotification::create([
            'id' => (string) Str::uuid(),
            'type' => 'App\\Notifications\\TestNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => ['message' => $message],
            'read_at' => null,
        ]);
    }

    #[Test]
    public function guest_cannot_mark_notifications_as_read(): void
    {
        $this->post(route('notificaciones.marcar.leidas'))
            ->assertRedirect(route('login'));
    }

    #[Test]
    public function authenticated_user_marks_only_own_unread_notifications(): void
    {
        $alice = $this->userWithRole('Cliente');
        $bob = $this->userWithRole('Cliente');

        $aliceNote = $this->createUnreadNotification($alice, 'para alice');
        $bobNote = $this->createUnreadNotification($bob, 'para bob');

        $this->actingAs($alice)
            ->from('/cliente/dashboard')
            ->post(route('notificaciones.marcar.leidas'))
            ->assertRedirect('/cliente/dashboard');

        $this->assertNotNull($aliceNote->fresh()->read_at);
        $this->assertNull($bobNote->fresh()->read_at);
    }

    #[Test]
    public function unverified_user_cannot_mark_notifications_as_read(): void
    {
        $user = User::factory()->unverified()->create();
        $user->assignRole('Cliente');
        $this->createUnreadNotification($user, 'pendiente');

        $this->actingAs($user)
            ->post(route('notificaciones.marcar.leidas'))
            ->assertRedirect(route('verification.notice'));
    }

    #[Test]
    public function clientes_dashboard_plural_is_not_registered(): void
    {
        $matches = collect(Route::getRoutes()->getRoutes())
            ->filter(fn ($r) => $r->uri() === 'clientes/dashboard');

        $this->assertCount(0, $matches);
    }

    #[Test]
    public function guest_is_redirected_from_cliente_dashboard_and_legacy_plural_is_404(): void
    {
        $this->get('/cliente/dashboard')->assertRedirect(route('login'));
        $this->get('/clientes/dashboard')->assertNotFound();
    }

    #[Test]
    public function cliente_dashboard_named_route_points_to_singular_uri_with_auth(): void
    {
        $route = Route::getRoutes()->getByName('cliente.dashboard');

        $this->assertNotNull($route);
        $this->assertSame('cliente/dashboard', $route->uri());
        $this->assertContains('auth', $route->gatherMiddleware());
    }

    #[Test]
    public function guest_cannot_open_role_dashboards_without_login(): void
    {
        foreach (['/admin/dashboard', '/superadmin/dashboard', '/paseador/dashboard'] as $uri) {
            $this->get($uri)->assertRedirect(route('login'));
        }
    }

    #[Test]
    public function login_dashboard_named_routes_exist_with_auth(): void
    {
        foreach (['superadmin.dashboard', 'admin.dashboard', 'cliente.dashboard', 'paseador.dashboard'] as $name) {
            $route = Route::getRoutes()->getByName($name);
            $this->assertNotNull($route, "Falta la ruta nombrada {$name}");
            $this->assertContains('auth', $route->gatherMiddleware(), "{$name} sin auth");
        }

        $superadmin = Route::getRoutes()->getByName('superadmin.dashboard');
        $this->assertContains('verified', $superadmin->gatherMiddleware());
        $this->assertContains('role:Superadmin', $superadmin->gatherMiddleware());
    }

    #[Test]
    public function login_superadmin_unprotected_action_is_gone_and_admin_login_requires_auth(): void
    {
        $actions = collect(Route::getRoutes()->getRoutes())
            ->map(fn ($r) => $r->getActionName())
            ->all();

        $this->assertNotContains(
            'App\Http\Controllers\SuperadminController@login_Superadmin',
            $actions,
            'login_Superadmin sin auth no debe registrarse'
        );

        $adminLogin = collect(Route::getRoutes()->getRoutes())
            ->first(fn ($r) => $r->getActionName() === 'App\Http\Controllers\AdminController@login_Admin');

        $this->assertNotNull($adminLogin);
        $this->assertContains('auth', $adminLogin->gatherMiddleware());
    }

    #[Test]
    public function empresas_pdf_remains_registered_once_with_full_middleware(): void
    {
        $matches = collect(Route::getRoutes()->getRoutes())
            ->filter(fn ($r) => $r->uri() === 'empresas/{empresa}/pdf');

        $this->assertCount(1, $matches);
        $middleware = $matches->first()->gatherMiddleware();
        $this->assertContains('auth', $middleware);
        $this->assertContains('verified', $middleware);
        $this->assertContains('role:Superadmin|Admin', $middleware);
    }
}
