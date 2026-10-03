<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * SEG-031b: tras verificar el correo cada rol debe ir a su dashboard
 * (antes todos iban a /superadmin/dashboard) y las filas de email_configs
 * con password indescifrable deben quedar desactivadas.
 */
class FortifyVerificationRedirectTest extends TestCase
{
    use RefreshDatabase;

    public static function roleProvider(): array
    {
        return [
            'Superadmin' => ['Superadmin', 'superadmin.dashboard'],
            'Admin' => ['Admin', 'admin.dashboard'],
            'Cliente' => ['Cliente', 'cliente.dashboard'],
            'Paseador' => ['Paseador', 'paseador.dashboard'],
        ];
    }

    #[Test]
    #[DataProvider('roleProvider')]
    public function verifying_email_redirects_to_role_dashboard(string $role, string $routeName): void
    {
        Event::fake([Verified::class]);
        Role::create(['name' => $role]);
        $user = User::factory()->unverified()->create();
        $user->assignRole($role);

        $url = URL::temporarySignedRoute('verification.verify', Carbon::now()->addMinutes(60), [
            'id' => $user->id,
            'hash' => sha1($user->getEmailForVerification()),
        ]);

        $this->actingAs($user)->get($url)->assertRedirect(route($routeName));
        Event::assertDispatched(Verified::class);
    }

    #[Test]
    public function migration_deactivates_only_undecryptable_email_configs(): void
    {
        $base = [
            'mailer' => 'smtp', 'host' => 'h', 'port' => 587, 'username' => 'u',
            'encryption' => 'tls', 'from_address' => 'a@example.test',
            'created_at' => now(), 'updated_at' => now(),
        ];
        $badId = DB::table('email_configs')->insertGetId($base + ['password' => 'eyJpdiI6ImJhZCJ9', 'is_active' => true]);
        $goodId = DB::table('email_configs')->insertGetId($base + [
            'password' => \Illuminate\Support\Facades\Crypt::encryptString('ok'), 'is_active' => true,
        ]);

        $migration = require database_path('migrations/2026_10_03_000000_deactivate_undecryptable_email_configs.php');
        $migration->up();

        $this->assertEquals(0, DB::table('email_configs')->where('id', $badId)->value('is_active'));
        $this->assertEquals(1, DB::table('email_configs')->where('id', $goodId)->value('is_active'));
    }
}
