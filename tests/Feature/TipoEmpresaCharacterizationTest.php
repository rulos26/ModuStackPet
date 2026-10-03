<?php

namespace Tests\Feature;

use App\Models\TipoEmpresa;
use App\Models\TiposEmpresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * SEG-036: caracterización de TipoEmpresa / TiposEmpresa (misma tabla
 * `tipos_empresas`) y del CRUD /tipos-empresas antes de consolidar.
 */
class TipoEmpresaCharacterizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create();
        $admin->assignRole(Role::create(['name' => 'Admin']));
        $this->actingAs($admin);
    }

    private function row(string $nombre = 'SAS'): int
    {
        return DB::table('tipos_empresas')->insertGetId(['nombre' => $nombre, 'created_at' => now(), 'updated_at' => now()]);
    }

    #[Test]
    public function both_models_share_the_table_and_read_the_same_row(): void
    {
        $id = $this->row();

        $this->assertSame('tipos_empresas', (new TipoEmpresa)->getTable());
        $this->assertSame('tipos_empresas', (new TiposEmpresa)->getTable());
        $this->assertSame('SAS', TipoEmpresa::find($id)->nombre);
        $this->assertSame('SAS', TiposEmpresa::find($id)->nombre);
    }

    #[Test]
    public function delete_is_hard_for_tiposempresa_and_soft_for_tipoempresa(): void
    {
        $a = $this->row('A');
        $b = $this->row('B');

        TiposEmpresa::find($a)->delete();
        $this->assertDatabaseMissing('tipos_empresas', ['id' => $a]);

        TipoEmpresa::find($b)->delete();
        $this->assertNotNull(DB::table('tipos_empresas')->where('id', $b)->value('deleted_at'));
    }

    #[Test]
    public function tipoempresa_has_empresas_relation_tiposempresa_does_not(): void
    {
        $this->assertTrue(method_exists(TipoEmpresa::class, 'empresas'));
        $this->assertFalse(method_exists(TiposEmpresa::class, 'empresas'));
    }

    #[Test]
    public function index_lists_twenty_per_page(): void
    {
        for ($i = 1; $i <= 21; $i++) {
            $this->row(sprintf('Tipo %02d', $i));
        }

        $this->get('/tipos-empresas')->assertOk()->assertSee('Tipo 20')->assertDontSee('Tipo 21');
    }

    #[Test]
    public function crud_works_end_to_end(): void
    {
        $this->post('/tipos-empresas', ['nombre' => 'LTDA'])->assertRedirect(route('tipos-empresas.index'));
        $id = DB::table('tipos_empresas')->where('nombre', 'LTDA')->value('id');
        $this->assertNotNull($id);

        $this->get("/tipos-empresas/{$id}")->assertOk()->assertSee('LTDA');
        $this->get("/tipos-empresas/{$id}/edit")->assertOk();

        $this->put("/tipos-empresas/{$id}", ['nombre' => 'LTDA 2'])->assertRedirect(route('tipos-empresas.index'));
        $this->assertSame('LTDA 2', DB::table('tipos_empresas')->where('id', $id)->value('nombre'));

        $this->delete("/tipos-empresas/{$id}")->assertRedirect(route('tipos-empresas.index'));
        $this->assertNull(TiposEmpresa::find($id));
    }

    #[Test]
    public function no_unique_rule_today_duplicates_are_accepted(): void
    {
        $this->row('SAS');

        $this->post('/tipos-empresas', ['nombre' => 'SAS'])->assertSessionHasNoErrors();
        $this->assertSame(2, DB::table('tipos_empresas')->where('nombre', 'SAS')->count());
    }

    #[Test]
    public function missing_id_currently_crashes(): void
    {
        $this->get('/tipos-empresas/9999/edit')->assertStatus(500);
        $this->delete('/tipos-empresas/9999')->assertStatus(500);
    }

    #[Test]
    public function routes_already_require_auth_and_role(): void
    {
        auth()->logout();
        $this->get('/tipos-empresas')->assertRedirect(route('login'));
    }

    #[Test]
    public function cliente_gets_403(): void
    {
        $cliente = User::factory()->create();
        $cliente->assignRole(Role::create(['name' => 'Cliente']));

        $this->actingAs($cliente)->get('/tipos-empresas')->assertForbidden();
    }
}
