<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Escalada de privilegios reportada en docs/auditorias/seg001-correccion.md:
 * /usuarios/roles solo exigía 'auth', así que cualquier usuario autenticado
 * podía asignar o quitar roles a cualquier otro usuario (incluido a sí
 * mismo), sin que su propio rol cambiara en la BD.
 */
class RoleAssignmentAccessTest extends TestCase
{
    use RefreshDatabase;

    private const NON_SUPERADMIN_ROLES = ['Admin', 'Cliente', 'Paseador'];

    protected function setUp(): void
    {
        parent::setUp();

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

    public static function nonSuperadminRolesProvider(): array
    {
        return array_map(fn (string $role) => [$role], self::NON_SUPERADMIN_ROLES);
    }

    #[Test]
    #[DataProvider('nonSuperadminRolesProvider')]
    public function non_superadmin_role_gets_403_on_roles_index(string $role): void
    {
        $user = $this->userWithRole($role);

        $response = $this->actingAs($user)->get(route('usuarios.roles.index'));

        $response->assertStatus(403);
    }

    #[Test]
    #[DataProvider('nonSuperadminRolesProvider')]
    public function non_superadmin_role_cannot_assign_roles_to_another_user(string $role): void
    {
        $user = $this->userWithRole($role);
        $target = $this->userWithRole('Cliente');

        $response = $this->actingAs($user)->post(route('usuarios.roles.asignar', $target), [
            'rol' => ['Superadmin'],
        ]);

        $target->refresh();
        $this->assertFalse($target->hasRole('Superadmin'), "El rol [{$role}] pudo asignar Superadmin a otro usuario.");
        $this->assertTrue($target->hasRole('Cliente'));
        $response->assertStatus(403);
    }

    #[Test]
    #[DataProvider('nonSuperadminRolesProvider')]
    public function non_superadmin_role_cannot_self_promote(string $role): void
    {
        $user = $this->userWithRole($role);

        $response = $this->actingAs($user)->post(route('usuarios.roles.asignar', $user), [
            'rol' => ['Superadmin'],
        ]);

        $user->refresh();
        $this->assertFalse($user->hasRole('Superadmin'), "El rol [{$role}] pudo auto-promoverse a Superadmin.");
        $this->assertTrue($user->hasRole($role));
        $response->assertStatus(403);
    }

    #[Test]
    public function superadmin_is_not_blocked_with_403_on_roles_index(): void
    {
        $superadmin = $this->userWithRole('Superadmin');

        $response = $this->actingAs($superadmin)->get(route('usuarios.roles.index'));

        $this->assertNotEquals(403, $response->getStatusCode());
    }
}
