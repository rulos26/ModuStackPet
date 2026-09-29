<?php

namespace Tests\Feature;

use App\Models\BackupConfig;
use App\Models\DatabaseConfig;
use App\Models\EmailConfig;
use App\Models\Module;
use App\Models\User;
use App\Services\BackupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminWebToolsTest extends TestCase
{
    use RefreshDatabase;

    private User $superadmin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'Superadmin']);
        $this->superadmin = User::factory()->create();
        $this->superadmin->assignRole('Superadmin');
    }

    public static function indexRoutesProvider(): array
    {
        return [
            ['superadmin.database-configs.index'],
            ['superadmin.email-configs.index'],
            ['superadmin.backup-configs.index'],
            ['superadmin.seeders.index'],
            ['superadmin.migrations.index'],
            ['superadmin.clean.index'],
        ];
    }

    #[Test]
    #[DataProvider('indexRoutesProvider')]
    public function admin_tool_indexes_are_hidden_when_disabled(string $routeName): void
    {
        config(['admin_tools.enabled' => false]);

        $this->actingAs($this->superadmin)
            ->get(route($routeName))
            ->assertNotFound();
    }

    #[Test]
    public function disabled_admin_tool_actions_do_not_write_or_execute_anything(): void
    {
        config(['admin_tools.enabled' => false]);

        Artisan::shouldReceive('call')->never();

        $backupService = Mockery::mock(BackupService::class);
        $backupService->shouldNotReceive('executeBackup');
        $this->app->instance(BackupService::class, $backupService);

        $backupConfig = BackupConfig::create([
            'name' => 'Destino existente',
            'connection' => 'mysql',
            'host' => '127.0.0.1',
            'port' => 3306,
            'database' => 'backup_test',
            'username' => 'backup_user',
            'password' => 'backup_password',
            'execute_seeders' => false,
            'is_active' => true,
        ]);

        $databaseCount = DatabaseConfig::count();
        $emailCount = EmailConfig::count();
        $backupCount = BackupConfig::count();

        $this->actingAs($this->superadmin)
            ->post(route('superadmin.database-configs.store'), [
                'connection' => 'mysql',
                'host' => '127.0.0.1',
                'port' => 3306,
                'database' => 'blocked_database',
                'username' => 'blocked_user',
                'password' => 'blocked_password',
            ])
            ->assertNotFound();

        $this->actingAs($this->superadmin)
            ->post(route('superadmin.email-configs.store'), [
                'mailer' => 'smtp',
                'host' => 'smtp.example.test',
                'port' => 587,
                'username' => 'blocked@example.test',
                'password' => 'blocked_password',
                'encryption' => 'tls',
                'from_address' => 'blocked@example.test',
            ])
            ->assertNotFound();

        $this->actingAs($this->superadmin)
            ->post(route('superadmin.backup-configs.store'), [
                'name' => 'Destino bloqueado',
                'connection' => 'mysql',
                'host' => '127.0.0.1',
                'port' => 3306,
                'database' => 'blocked_backup',
                'username' => 'blocked_user',
                'password' => 'blocked_password',
            ])
            ->assertNotFound();

        $this->actingAs($this->superadmin)
            ->post(route('superadmin.backup-configs.execute', $backupConfig))
            ->assertNotFound();

        $this->actingAs($this->superadmin)
            ->post(route('superadmin.seeders.execute'), [
                'seeder' => 'Database\\Seeders\\ModuleSeeder',
            ])
            ->assertNotFound();

        $this->actingAs($this->superadmin)
            ->post(route('superadmin.migrations.execute'), ['tipo' => 'run'])
            ->assertNotFound();

        $this->assertSame($databaseCount, DatabaseConfig::count());
        $this->assertSame($emailCount, EmailConfig::count());
        $this->assertSame($backupCount, BackupConfig::count());

        $backupConfig->refresh();
        $this->assertNull($backupConfig->last_backup_at);
        $this->assertNull($backupConfig->last_backup_result);
    }

    #[Test]
    public function admin_tool_links_are_hidden_when_disabled_and_visible_when_enabled(): void
    {
        foreach (['database-config', 'email-config', 'backup-config'] as $slug) {
            Module::createQuietly([
                'name' => $slug,
                'slug' => $slug,
                'status' => true,
            ]);
        }

        $this->actingAs($this->superadmin);

        $toolRoutes = [
            'superadmin.database-configs.index',
            'superadmin.email-configs.index',
            'superadmin.backup-configs.index',
            'superadmin.seeders.index',
            'superadmin.migrations.index',
        ];

        config(['admin_tools.enabled' => false]);
        $disabledMenu = view('superadmin.sidebar')->render();

        foreach ($toolRoutes as $routeName) {
            $this->assertStringNotContainsString(route($routeName), $disabledMenu);
        }

        config(['admin_tools.enabled' => true]);
        $enabledMenu = view('superadmin.sidebar')->render();

        foreach ($toolRoutes as $routeName) {
            $this->assertStringContainsString(route($routeName), $enabledMenu);
        }
    }

    #[Test]
    #[DataProvider('indexRoutesProvider')]
    public function superadmin_can_view_admin_tool_indexes_when_enabled(string $routeName): void
    {
        config(['admin_tools.enabled' => true]);

        if ($routeName === 'superadmin.migrations.index') {
            Artisan::shouldReceive('call')->once()->andReturn(0);
        }

        $this->actingAs($this->superadmin)
            ->get(route($routeName))
            ->assertOk();
    }
}
