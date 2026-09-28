<?php

namespace Tests\Feature;

use App\Models\BackupConfig;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * SEG-001: el grupo /superadmin solo exigía 'auth' y 'verified', sin
 * comprobar el rol. Cualquier usuario autenticado (Admin, Cliente,
 * Paseador) podía acceder a las rutas administrativas.
 */
class SuperadminAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Nombres de roles tal como los crea database/seeders/roleSeeder.php.
     */
    private const NON_SUPERADMIN_ROLES = ['Admin', 'Cliente', 'Paseador'];

    private const INDEX_ROUTES = [
        'superadmin.dashboard',
        'superadmin.usuarios.index',
        'superadmin.database-configs.index',
        'superadmin.email-configs.index',
        'superadmin.backup-configs.index',
        'superadmin.migrations.index',
        'superadmin.oauth-providers.index',
        'superadmin.configuraciones.index',
    ];

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

    public static function indexRoutesProvider(): array
    {
        return array_map(fn (string $route) => [$route], self::INDEX_ROUTES);
    }

    #[Test]
    #[DataProvider('nonSuperadminRolesProvider')]
    public function non_superadmin_role_gets_403_on_every_superadmin_index_route(string $role): void
    {
        $user = $this->userWithRole($role);

        foreach (self::INDEX_ROUTES as $routeName) {
            $response = $this->actingAs($user)->get(route($routeName));

            $response->assertStatus(403, "La ruta [{$routeName}] no devolvió 403 para el rol [{$role}].");
        }
    }

    #[Test]
    #[DataProvider('nonSuperadminRolesProvider')]
    public function non_superadmin_role_gets_403_on_backup_configs_execute_and_db_is_unchanged(string $role): void
    {
        $user = $this->userWithRole($role);

        $backupConfig = BackupConfig::create([
            'name' => 'Backup de prueba',
            'connection' => 'mysql',
            'host' => '127.0.0.1',
            'port' => 3306,
            'database' => 'backup_test',
            'username' => 'usuario_test',
            'password' => 'password_test',
            'execute_seeders' => false,
            'is_active' => true,
        ]);

        $lastBackupAtBefore = $backupConfig->last_backup_at;
        $lastBackupResultBefore = $backupConfig->last_backup_result;

        $response = $this->actingAs($user)->post(route('superadmin.backup-configs.execute', $backupConfig));

        $response->assertStatus(403);

        $backupConfig->refresh();
        $this->assertEquals($lastBackupAtBefore, $backupConfig->last_backup_at);
        $this->assertEquals($lastBackupResultBefore, $backupConfig->last_backup_result);
    }

    #[Test]
    #[DataProvider('nonSuperadminRolesProvider')]
    public function non_superadmin_role_gets_403_on_migrations_execute(string $role): void
    {
        $user = $this->userWithRole($role);

        $response = $this->actingAs($user)->post(route('superadmin.migrations.execute'));

        $response->assertStatus(403);
    }

    #[Test]
    #[DataProvider('nonSuperadminRolesProvider')]
    public function non_superadmin_role_gets_403_on_database_configs_store_and_db_is_unchanged(string $role): void
    {
        $user = $this->userWithRole($role);

        $countBefore = \App\Models\DatabaseConfig::count();

        $response = $this->actingAs($user)->post(route('superadmin.database-configs.store'), [
            'name' => 'Config de prueba',
            'connection' => 'mysql',
            'host' => '127.0.0.1',
            'port' => 3306,
            'database' => 'test_db',
            'username' => 'usuario_test',
            'password' => 'password_test',
        ]);

        $response->assertStatus(403);
        $this->assertEquals($countBefore, \App\Models\DatabaseConfig::count());
    }

    #[Test]
    #[DataProvider('nonSuperadminRolesProvider')]
    public function non_superadmin_role_gets_403_on_usuarios_store_and_db_is_unchanged(string $role): void
    {
        $user = $this->userWithRole($role);

        $countBefore = User::count();

        $response = $this->actingAs($user)->post(route('superadmin.usuarios.store'), [
            'name' => 'Usuario de prueba',
            'email' => 'nuevo-'.uniqid().'@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(403);
        $this->assertEquals($countBefore, User::count());
    }

    #[Test]
    public function guest_is_redirected_to_login_on_superadmin_dashboard(): void
    {
        $response = $this->get(route('superadmin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    #[DataProvider('indexRoutesProvider')]
    public function superadmin_role_is_not_blocked_with_403_on_index_routes(string $routeName): void
    {
        $superadmin = $this->userWithRole('Superadmin');

        $response = $this->actingAs($superadmin)->get(route($routeName));

        $this->assertNotEquals(403, $response->getStatusCode(), "La ruta [{$routeName}] devolvió 403 para Superadmin.");
    }
}
