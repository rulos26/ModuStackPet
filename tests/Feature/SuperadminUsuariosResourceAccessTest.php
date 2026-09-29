<?php

namespace Tests\Feature;

use App\Models\TipoDocumento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * SEG-020: antes probaba el resource duplicado `users.*` (ver SEG-011).
 * Ese resource se retiró de routes/web.php porque exponía las mismas siete
 * acciones que `superadmin.usuarios.*`, ya elegido como ruta canónica. Esta
 * prueba se renombró y sus rutas se migraron a `superadmin.usuarios.*` para
 * no perder la cobertura de invitado/roles no autorizados/Superadmin en las
 * siete acciones (index, show, create, edit, store, update, destroy).
 */
class SuperadminUsuariosResourceAccessTest extends TestCase
{
    use RefreshDatabase;

    private const NON_SUPERADMIN_ROLES = ['Admin', 'Cliente', 'Paseador'];

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        Role::create(['name' => 'Superadmin']);
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Cliente']);
        Role::create(['name' => 'Paseador']);
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    public static function resourceRoutesProvider(): array
    {
        return [
            'index' => ['get', 'superadmin.usuarios.index'],
            'show' => ['get', 'superadmin.usuarios.show'],
            'create' => ['get', 'superadmin.usuarios.create'],
            'edit' => ['get', 'superadmin.usuarios.edit'],
            'store' => ['post', 'superadmin.usuarios.store'],
            'update' => ['put', 'superadmin.usuarios.update'],
            'destroy' => ['delete', 'superadmin.usuarios.destroy'],
        ];
    }

    public static function nonSuperadminResourceRoutesProvider(): array
    {
        $cases = [];

        foreach (self::NON_SUPERADMIN_ROLES as $role) {
            foreach (self::resourceRoutesProvider() as $route => [$method, $routeName]) {
                $cases["{$role} - {$route}"] = [$role, $method, $routeName];
            }
        }

        return $cases;
    }

    public static function superadminReadRoutesProvider(): array
    {
        return [
            'index' => ['superadmin.usuarios.index'],
            'show' => ['superadmin.usuarios.show'],
        ];
    }

    #[Test]
    #[DataProvider('resourceRoutesProvider')]
    public function guest_is_redirected_to_login_on_every_resource_route(string $method, string $routeName): void
    {
        $target = User::factory()->create();
        $countBefore = User::count();
        $nameBefore = $target->name;

        $response = $this->callResourceRoute($method, $routeName, $target);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseStateUnchanged($routeName, $target, $countBefore, $nameBefore);
    }

    #[Test]
    #[DataProvider('nonSuperadminResourceRoutesProvider')]
    public function non_superadmin_role_gets_403_and_cannot_mutate_users(
        string $role,
        string $method,
        string $routeName,
    ): void {
        $actor = $this->userWithRole($role);
        $target = User::factory()->create();
        $countBefore = User::count();
        $nameBefore = $target->name;

        $response = $this->actingAs($actor)->callResourceRoute($method, $routeName, $target);

        $response->assertStatus(403);
        $this->assertDatabaseStateUnchanged($routeName, $target, $countBefore, $nameBefore);
    }

    #[Test]
    #[DataProvider('superadminReadRoutesProvider')]
    public function superadmin_is_not_blocked_with_403_on_read_routes(string $routeName): void
    {
        $superadmin = $this->userWithRole('Superadmin');
        $target = User::factory()->create();

        $response = $this->actingAs($superadmin)->get(route($routeName, $target));

        $this->assertNotSame(403, $response->getStatusCode());
    }

    private function callResourceRoute(string $method, string $routeName, User $target)
    {
        $parameters = str_contains($routeName, 'index') || str_contains($routeName, 'create')
            ? []
            : [$target];

        $data = in_array($routeName, ['superadmin.usuarios.store', 'superadmin.usuarios.update'], true)
            ? $this->validUserData($routeName)
            : [];

        return $this->{$method}(route($routeName, $parameters), $data);
    }

    private function validUserData(string $routeName): array
    {
        $tipoDocumento = TipoDocumento::firstOrCreate(['nombre' => 'Cédula de ciudadanía']);

        return [
            'name' => $routeName === 'superadmin.usuarios.store' ? 'Usuario creado sin permiso' : 'Usuario alterado sin permiso',
            'email' => $routeName === 'superadmin.usuarios.store' ? 'creado@example.com' : 'alterado@example.com',
            'tipo_documento' => $tipoDocumento->id,
            'cedula' => $routeName === 'superadmin.usuarios.store' ? '123456789' : '987654321',
            'fecha_nacimiento' => '1990-01-01',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'activo' => true,
        ];
    }

    private function assertDatabaseStateUnchanged(
        string $routeName,
        User $target,
        int $countBefore,
        string $nameBefore,
    ): void {
        if (! in_array($routeName, ['superadmin.usuarios.store', 'superadmin.usuarios.update', 'superadmin.usuarios.destroy'], true)) {
            return;
        }

        $this->assertSame($countBefore, User::count());
        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'name' => $nameBefore,
        ]);
    }
}
