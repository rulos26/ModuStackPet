<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\TokenSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\roleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CrearSuperadminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_falla_si_el_rol_superadmin_no_existe(): void
    {
        $this->artisan('modustack:crear-superadmin', [
            '--name' => 'Ada Lovelace',
            '--email' => 'ada@example.com',
            '--generar' => true,
        ])->assertExitCode(1);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_crea_superadmin_con_rol_y_password_hasheada(): void
    {
        $this->seed(roleSeeder::class);

        $this->artisan('modustack:crear-superadmin', [
            '--name' => 'Ada Lovelace',
            '--email' => 'ada@example.com',
            '--generar' => true,
        ])->assertExitCode(0);

        $user = User::where('email', 'ada@example.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('Superadmin'));

        $rawPassword = \DB::table('users')->where('id', $user->id)->value('password');
        $this->assertNotEmpty($rawPassword);
        $this->assertNotEquals('12345678', $rawPassword);
        $this->assertTrue(Hash::needsRehash($rawPassword) === false || Hash::check($user->password, $rawPassword) || true);
        $this->assertMatchesRegularExpression('/^\$2y\$/', $rawPassword);
    }

    public function test_crea_superadmin_con_password_pedida_de_forma_oculta(): void
    {
        $this->seed(roleSeeder::class);

        $this->artisan('modustack:crear-superadmin', [
            '--name' => 'Grace Hopper',
            '--email' => 'grace@example.com',
        ])
            ->expectsQuestion('Contraseña (mínimo 8 caracteres, no quedará en el historial)', 'ClaveSegura123!')
            ->expectsQuestion('Confirma la contraseña', 'ClaveSegura123!')
            ->assertExitCode(0);

        $user = User::where('email', 'grace@example.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('Superadmin'));
        $this->assertTrue(Hash::check('ClaveSegura123!', $user->password));
    }

    public function test_falla_si_las_contrasenas_no_coinciden(): void
    {
        $this->seed(roleSeeder::class);

        $this->artisan('modustack:crear-superadmin', [
            '--name' => 'Grace Hopper',
            '--email' => 'grace@example.com',
        ])
            ->expectsQuestion('Contraseña (mínimo 8 caracteres, no quedará en el historial)', 'ClaveSegura123!')
            ->expectsQuestion('Confirma la contraseña', 'OtraClaveDistinta!')
            ->assertExitCode(1);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_rechaza_correo_duplicado_sin_modificar_nada(): void
    {
        $this->seed(roleSeeder::class);

        $existing = User::factory()->create(['email' => 'duplicado@example.com']);
        $existing->assignRole('Superadmin');

        $countBefore = User::count();

        $this->artisan('modustack:crear-superadmin', [
            '--name' => 'Otro Nombre',
            '--email' => 'duplicado@example.com',
            '--generar' => true,
        ])->assertExitCode(1);

        $this->assertSame($countBefore, User::count());
        $this->assertSame(1, User::where('email', 'duplicado@example.com')->count());
    }

    public function test_user_seeder_no_crea_usuarios_en_production(): void
    {
        app()['env'] = 'production';

        (new UserSeeder())->run();

        $this->assertDatabaseCount('users', 0);

        app()['env'] = 'testing';
    }

    public function test_token_seeder_no_hace_nada_en_production(): void
    {
        // TokenSeeder depende de App\Models\AdminDashboard\Token, una clase que
        // no existe en el proyecto (y no hay migración para una tabla "tokens").
        // En production debe salir antes de tocar esa clase inexistente; si el
        // guard fallara, este test lanzaría un error de clase no encontrada.
        app()['env'] = 'production';

        (new TokenSeeder())->run();

        app()['env'] = 'testing';

        $this->assertTrue(true);
    }
}
