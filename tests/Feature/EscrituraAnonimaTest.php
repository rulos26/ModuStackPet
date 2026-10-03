<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * SEG-040: los 7 recursos de catálogo que permitían escritura anónima ahora
 * exigen auth + verified + rol. Superadmin y Admin donde el sidebar de Admin
 * enlaza al recurso (paths-documentos, tipos-empresas, tipo-documentos,
 * sectores); solo Superadmin para razas, barrios y mensaje-de-bienvenidas
 * (solo aparecen en el sidebar de Superadmin).
 */
class EscrituraAnonimaTest extends TestCase
{
    use RefreshDatabase;

    private const SUPERADMIN_AND_ADMIN = ['paths-documentos', 'tipos-empresas', 'tipo-documentos', 'sectores'];

    private const SUPERADMIN_ONLY = ['razas', 'barrios', 'mensaje-de-bienvenidas'];

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['Superadmin', 'Admin', 'Cliente', 'Paseador'] as $role) {
            Role::create(['name' => $role]);
        }
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    public static function resourcesProvider(): array
    {
        return array_map(fn ($r) => [$r], [...self::SUPERADMIN_AND_ADMIN, ...self::SUPERADMIN_ONLY]);
    }

    /** @return array<string, array{string,string}> */
    private function requests(string $resource): array
    {
        return [
            'index' => ['get', "/{$resource}"],
            'create' => ['get', "/{$resource}/create"],
            'store' => ['post', "/{$resource}"],
            'show' => ['get', "/{$resource}/1"],
            'edit' => ['get', "/{$resource}/1/edit"],
            'update' => ['put', "/{$resource}/1"],
            'destroy' => ['delete', "/{$resource}/1"],
        ];
    }

    #[Test]
    #[DataProvider('resourcesProvider')]
    public function guest_is_redirected_to_login_on_every_action(string $resource): void
    {
        foreach ($this->requests($resource) as $label => [$method, $uri]) {
            $this->$method($uri)->assertRedirect(route('login'), "[{$resource} {$label}] no redirigió al invitado");
        }
    }

    #[Test]
    public function paths_documentos_toggle_requires_login(): void
    {
        $this->post('/paths-documentos/1/toggle-status')->assertRedirect(route('login'));
    }

    #[Test]
    public function paths_documentos_does_not_leak_user_emails_or_cedulas_to_guests(): void
    {
        User::factory()->create(['email' => 'secreto@example.test', 'cedula' => '99887766']);

        $this->get('/paths-documentos/create')->assertRedirect(route('login'));
        $body = $this->get('/paths-documentos')->getContent();

        $this->assertStringNotContainsString('secreto@example.test', $body);
        $this->assertStringNotContainsString('99887766', $body);
    }

    #[Test]
    #[DataProvider('resourcesProvider')]
    public function cliente_and_paseador_get_403(string $resource): void
    {
        foreach (['Cliente', 'Paseador'] as $role) {
            $user = $this->userWithRole($role);

            foreach ($this->requests($resource) as $label => [$method, $uri]) {
                $this->actingAs($user)->$method($uri)
                    ->assertForbidden("[{$resource} {$label}] no dio 403 a {$role}");
            }
        }
    }

    #[Test]
    #[DataProvider('resourcesProvider')]
    public function unverified_user_is_sent_to_verification(string $resource): void
    {
        $user = User::factory()->unverified()->create();
        $user->assignRole('Superadmin');

        $this->actingAs($user)->get("/{$resource}")->assertRedirect(route('verification.notice'));
    }

    #[Test]
    #[DataProvider('resourcesProvider')]
    public function superadmin_can_open_index(string $resource): void
    {
        $this->actingAs($this->userWithRole('Superadmin'))->get("/{$resource}")->assertOk();
    }

    public static function adminAllowedProvider(): array
    {
        return array_map(fn ($r) => [$r], self::SUPERADMIN_AND_ADMIN);
    }

    #[Test]
    #[DataProvider('adminAllowedProvider')]
    public function admin_can_open_index_of_resources_in_its_sidebar(string $resource): void
    {
        $this->actingAs($this->userWithRole('Admin'))->get("/{$resource}")->assertOk();
    }

    public static function superadminOnlyProvider(): array
    {
        return array_map(fn ($r) => [$r], self::SUPERADMIN_ONLY);
    }

    #[Test]
    #[DataProvider('superadminOnlyProvider')]
    public function admin_gets_403_on_superadmin_only_resources(string $resource): void
    {
        $this->actingAs($this->userWithRole('Admin'))->get("/{$resource}")->assertForbidden();
    }

    #[Test]
    public function guest_cannot_delete_or_create_a_raza(): void
    {
        $id = DB::table('razas')->insertGetId(['nombre' => 'Intocable', 'created_at' => now(), 'updated_at' => now()]);

        $this->delete("/razas/{$id}")->assertRedirect(route('login'));
        $this->post('/razas', ['nombre' => 'Anonima'])->assertRedirect(route('login'));

        $this->assertDatabaseHas('razas', ['id' => $id]);
        $this->assertDatabaseMissing('razas', ['nombre' => 'Anonima']);
    }
}
