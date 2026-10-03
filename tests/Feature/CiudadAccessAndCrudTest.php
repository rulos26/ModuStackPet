<?php

namespace Tests\Feature;

use App\Models\Ciudad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * SEG-034b: /ciudades/* exige sesión verificada y rol Superadmin o Admin
 * (el sidebar de ambos roles enlaza al módulo); y el CRUD funciona.
 */
class CiudadAccessAndCrudTest extends TestCase
{
    use RefreshDatabase;

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

    private function departamento(): int
    {
        return DB::table('departamentos')->insertGetId([
            'nombre' => 'Cundinamarca', 'estado' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function ciudadRow(int $dep, string $nombre = 'Bogotá', int $estado = 1): int
    {
        return DB::table('ciudades')->insertGetId([
            'municipio' => $nombre, 'departamento_id' => $dep, 'estado' => $estado,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    /** Peticiones que antes pasaban sin sesión. */
    private function requests(int $id): array
    {
        return [
            'index' => ['get', '/ciudades'],
            'show' => ['get', "/ciudades/{$id}"],
            'create form' => ['get', '/ciudades/create'],
            'edit form' => ['get', "/ciudades/{$id}/edit"],
            'toggle' => ['post', "/ciudades/{$id}/toggle-status"],
            'destroy' => ['delete', "/ciudades/{$id}"],
        ];
    }

    #[Test]
    public function guest_is_redirected_to_login_and_nothing_changes(): void
    {
        $id = $this->ciudadRow($this->departamento(), 'Intocable', 0);

        foreach ($this->requests($id) as $label => [$method, $uri]) {
            $this->$method($uri)->assertRedirect(route('login'), "[{$label}] no redirigió al invitado");
        }

        $this->assertDatabaseHas('ciudades', ['id_municipio' => $id, 'estado' => 0, 'deleted_at' => null]);
    }

    public static function forbiddenRolesProvider(): array
    {
        return [['Cliente'], ['Paseador']];
    }

    #[Test]
    #[DataProvider('forbiddenRolesProvider')]
    public function non_admin_roles_get_403(string $role): void
    {
        $id = $this->ciudadRow($this->departamento(), 'Intocable', 0);
        $user = $this->userWithRole($role);

        foreach ($this->requests($id) as $label => [$method, $uri]) {
            $this->actingAs($user)->$method($uri)->assertForbidden("[{$label}] no dio 403 a {$role}");
        }

        $this->assertDatabaseHas('ciudades', ['id_municipio' => $id, 'estado' => 0, 'deleted_at' => null]);
    }

    public static function allowedRolesProvider(): array
    {
        return [['Superadmin'], ['Admin']];
    }

    #[Test]
    #[DataProvider('allowedRolesProvider')]
    public function admin_roles_can_list_and_toggle(string $role): void
    {
        $id = $this->ciudadRow($this->departamento(), 'Zipaquirá');
        $user = $this->userWithRole($role);

        $this->actingAs($user)->get('/ciudades')->assertOk()->assertSee('Zipaquirá');
        $this->actingAs($user)->post("/ciudades/{$id}/toggle-status")->assertRedirect(route('ciudades.index'));
        $this->assertEquals(0, DB::table('ciudades')->where('id_municipio', $id)->value('estado'));
    }

    #[Test]
    public function unverified_user_is_redirected_to_verification(): void
    {
        $user = User::factory()->unverified()->create();
        $user->assignRole('Admin');

        $this->actingAs($user)->get('/ciudades')->assertRedirect(route('verification.notice'));
    }

    #[Test]
    public function create_and_edit_forms_render_with_departamentos(): void
    {
        $dep = $this->departamento();
        $id = $this->ciudadRow($dep, 'Vieja');
        $admin = $this->userWithRole('Admin');

        $this->actingAs($admin)->get('/ciudades/create')->assertOk()->assertSee('Cundinamarca');
        $this->actingAs($admin)->get("/ciudades/{$id}/edit")->assertOk()->assertSee('Vieja');
    }

    #[Test]
    public function store_creates_a_ciudad(): void
    {
        $dep = $this->departamento();
        $admin = $this->userWithRole('Admin');

        $this->actingAs($admin)->post('/ciudades', [
            'municipio' => 'Nueva', 'departamento_id' => $dep, 'estado' => 1,
        ])->assertRedirect(route('ciudades.index'))->assertSessionHasNoErrors();

        $this->assertDatabaseHas('ciudades', ['municipio' => 'Nueva', 'departamento_id' => $dep]);
    }

    #[Test]
    public function store_rejects_unknown_departamento_and_duplicates(): void
    {
        $dep = $this->departamento();
        $this->ciudadRow($dep, 'Repetida');
        $admin = $this->userWithRole('Admin');

        $this->actingAs($admin)->post('/ciudades', ['municipio' => 'X', 'departamento_id' => 9999, 'estado' => 1])
            ->assertSessionHasErrors('departamento_id');
        $this->actingAs($admin)->post('/ciudades', ['municipio' => 'Repetida', 'departamento_id' => $dep, 'estado' => 1])
            ->assertSessionHasErrors('municipio');
    }

    #[Test]
    public function update_changes_the_right_ciudad(): void
    {
        $dep = $this->departamento();
        $id = $this->ciudadRow($dep, 'Vieja');
        $otra = $this->ciudadRow($dep, 'Otra');
        $admin = $this->userWithRole('Admin');

        $this->actingAs($admin)->put("/ciudades/{$id}", [
            'municipio' => 'Renombrada', 'departamento_id' => $dep, 'estado' => 0,
        ])->assertRedirect(route('ciudades.index'))->assertSessionHasNoErrors();

        $this->assertSame('Renombrada', Ciudad::find($id)->municipio);
        $this->assertFalse(Ciudad::find($id)->estado);
        $this->assertSame('Otra', Ciudad::find($otra)->municipio);
    }

    #[Test]
    public function update_allows_keeping_own_name(): void
    {
        $dep = $this->departamento();
        $id = $this->ciudadRow($dep, 'Igual');
        $admin = $this->userWithRole('Admin');

        $this->actingAs($admin)->put("/ciudades/{$id}", [
            'municipio' => 'Igual', 'departamento_id' => $dep, 'estado' => 1,
        ])->assertSessionHasNoErrors();
    }
}
