<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * SEG-041: auth (y verified) deben estar en la ruta de departamentos,
 * empresas y vacunas_certificaciones — no solo en el constructor.
 */
class CatalogRouteAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['Superadmin', 'Admin', 'Cliente', 'Paseador'] as $role) {
            Role::create(['name' => $role]);
        }
    }

    private function userWithRole(string $role, bool $verified = true): User
    {
        $user = $verified
            ? User::factory()->create()
            : User::factory()->unverified()->create();
        $user->assignRole($role);

        return $user;
    }

    private function ensureModule(string $slug, bool $active = true): void
    {
        Module::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'name' => ucwords(str_replace(['-', '_'], ' ', $slug)),
                'description' => 'test',
                'status' => $active,
            ]
        );
    }

    /** @return list<string> */
    private function protectedRouteNames(): array
    {
        return [
            'departamentos.index',
            'empresas.index',
            'vacunas_certificaciones.index',
        ];
    }

    /**
     * Documenta el hueco actual: las rutas NO declaran `auth`
     * (solo CheckModuleStatus; el `auth` del constructor aparece en
     * gatherMiddleware pero NO en middleware() de la ruta).
     * Este test debe FALLAR hasta que auth esté en la ruta.
     */
    #[Test]
    public function route_definitions_include_auth_middleware(): void
    {
        foreach ($this->protectedRouteNames() as $name) {
            $route = app('router')->getRoutes()->getByName($name);
            $this->assertNotNull($route, "ruta {$name} inexistente");

            // Solo middleware declarado en routes/web.php (no el del controlador).
            $middleware = $route->middleware();
            $this->assertTrue(
                collect($middleware)->contains('auth'),
                "[{$name}] debe declarar middleware auth en la ruta; actual: ".implode(', ', $middleware)
            );
        }
    }

    /**
     * Con módulo inactivo y sin auth en la ruta, el invitado recibe 403
     * del CheckModuleStatus (prueba de que el módulo corre sin sesión).
     * Tras poner auth en la ruta, el invitado debe ir a login primero.
     */
    #[Test]
    #[DataProvider('moduleSlugsProvider')]
    public function guest_hits_auth_before_inactive_module_gate(string $slug, string $uri): void
    {
        $this->ensureModule($slug, active: false);

        $this->get($uri)->assertRedirect(route('login'));
    }

    public static function moduleSlugsProvider(): array
    {
        return [
            'departamentos' => ['departamentos', '/departamentos'],
            'empresas' => ['empresas', '/empresas'],
            'vacunas' => ['certificados', '/vacunas_certificaciones'],
        ];
    }

    #[Test]
    #[DataProvider('indexUrisProvider')]
    public function guest_is_redirected_to_login_on_index(string $uri): void
    {
        $this->ensureModule(match ($uri) {
            '/departamentos' => 'departamentos',
            '/empresas' => 'empresas',
            '/vacunas_certificaciones' => 'certificados',
        });

        $this->get($uri)->assertRedirect(route('login'));
    }

    public static function indexUrisProvider(): array
    {
        return [
            'departamentos' => ['/departamentos'],
            'empresas' => ['/empresas'],
            'vacunas' => ['/vacunas_certificaciones'],
        ];
    }

    #[Test]
    public function unverified_admin_is_redirected_to_verification_on_departamentos_and_empresas(): void
    {
        $this->ensureModule('departamentos');
        $this->ensureModule('empresas');
        $user = $this->userWithRole('Admin', verified: false);

        $this->actingAs($user)->get('/departamentos')->assertRedirect(route('verification.notice'));
        $this->actingAs($user)->get('/empresas')->assertRedirect(route('verification.notice'));
    }

    #[Test]
    #[DataProvider('forbiddenRolesProvider')]
    public function cliente_and_paseador_get_403_on_departamentos_and_empresas(string $role): void
    {
        $this->ensureModule('departamentos');
        $this->ensureModule('empresas');
        $user = $this->userWithRole($role);

        $this->actingAs($user)->get('/departamentos')->assertForbidden();
        $this->actingAs($user)->get('/empresas')->assertForbidden();
    }

    public static function forbiddenRolesProvider(): array
    {
        return [['Cliente'], ['Paseador']];
    }

    #[Test]
    #[DataProvider('allowedRolesProvider')]
    public function admin_roles_can_open_departamentos_index(string $role): void
    {
        $this->ensureModule('departamentos');
        $user = $this->userWithRole($role);

        $this->actingAs($user)->get('/departamentos')->assertOk();
    }

    public static function allowedRolesProvider(): array
    {
        return [['Superadmin'], ['Admin']];
    }

    #[Test]
    public function verified_cliente_can_open_vacunas_index(): void
    {
        $this->ensureModule('certificados');
        $user = $this->userWithRole('Cliente');

        $this->actingAs($user)->get('/vacunas_certificaciones')->assertOk();
    }

    /**
     * tipos-empresas sigue fuera de esta tarea (040): no debe ganar auth
     * solo porque compartía grupo de módulo con empresas.
     */
    #[Test]
    public function tipos_empresas_route_is_not_accidentally_given_auth_by_this_task(): void
    {
        $route = app('router')->getRoutes()->getByName('tipos-empresas.index');
        $this->assertNotNull($route);
        $middleware = $route->gatherMiddleware();

        $this->assertFalse(
            collect($middleware)->contains('auth'),
            'tipos-empresas.index no debe recibir auth en la tarea 041 (es 040); actual: '.implode(', ', $middleware)
        );
    }
}
