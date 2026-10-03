<?php

namespace Tests\Feature;

use App\Models\Ciudad;
use App\Models\Departamento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * SEG-034: consolidación Ciudad / Ciudade (misma tabla `ciudades`).
 * Antes del cambio estas pruebas describían ambos modelos (ver el commit
 * "test: caracterización de Ciudad/Ciudade"); ahora fijan el resultado:
 * solo existe Ciudad.
 */
class CiudadCharacterizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Desde SEG-034b /ciudades exige Superadmin o Admin.
        $admin = User::factory()->create();
        $admin->assignRole(Role::create(['name' => 'Admin']));
        $this->actingAs($admin);
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

    #[Test]
    public function ciudad_is_the_only_model_for_ciudades_table(): void
    {
        $this->assertFileDoesNotExist(app_path('Models/Ciudade.php'));
        $this->assertSame('ciudades', (new Ciudad)->getTable());
        $this->assertSame('id_municipio', (new Ciudad)->getKeyName());
    }

    #[Test]
    public function ciudad_reads_row_and_departamento(): void
    {
        $id = $this->ciudadRow($this->departamento());

        $c = Ciudad::findOrFail($id);
        $this->assertSame('Bogotá', $c->municipio);
        $this->assertSame('Cundinamarca', $c->departamento->nombre);
        // Los comparadores de las vistas (`== 1`) funcionan con el cast booleano.
        $this->assertTrue($c->estado);
        $this->assertTrue($c->estado == 1);
    }

    #[Test]
    public function ciudad_delete_is_soft(): void
    {
        $id = $this->ciudadRow($this->departamento(), 'B');

        Ciudad::find($id)->delete();

        $this->assertNotNull(DB::table('ciudades')->where('id_municipio', $id)->value('deleted_at'));
        $this->assertNull(Ciudad::find($id));
    }

    #[Test]
    public function departamento_ciudades_returns_ciudad_instances(): void
    {
        $dep = $this->departamento();
        $this->ciudadRow($dep, 'A');
        $this->ciudadRow($dep, 'B');

        $ciudades = Departamento::find($dep)->ciudades;

        $this->assertCount(2, $ciudades);
        $this->assertContainsOnlyInstancesOf(Ciudad::class, $ciudades);
    }

    #[Test]
    public function ciudad_has_empresas_relation(): void
    {
        $this->assertTrue(method_exists(Ciudad::class, 'empresas'));
    }

    #[Test]
    public function ciudades_index_and_show_render(): void
    {
        $id = $this->ciudadRow($this->departamento(), 'Zipaquirá');

        $this->get('/ciudades')->assertOk()->assertSee('Zipaquirá');
        $this->get("/ciudades/{$id}")->assertOk()->assertSee('Zipaquirá');
    }

    #[Test]
    public function toggle_status_flips_estado(): void
    {
        $id = $this->ciudadRow($this->departamento());

        $this->post("/ciudades/{$id}/toggle-status")->assertRedirect(route('ciudades.index'));
        $this->assertEquals(0, DB::table('ciudades')->where('id_municipio', $id)->value('estado'));

        $this->post("/ciudades/{$id}/toggle-status");
        $this->assertEquals(1, DB::table('ciudades')->where('id_municipio', $id)->value('estado'));
    }

    #[Test]
    public function destroy_refuses_active_city_and_soft_deletes_inactive_one(): void
    {
        $dep = $this->departamento();
        $activa = $this->ciudadRow($dep, 'Activa', 1);
        $inactiva = $this->ciudadRow($dep, 'Inactiva', 0);

        $this->delete("/ciudades/{$activa}")->assertSessionHas('error');
        $this->assertDatabaseHas('ciudades', ['id_municipio' => $activa, 'deleted_at' => null]);

        $this->delete("/ciudades/{$inactiva}")->assertSessionHas('success');
        // Cambio intencional de SEG-034: antes (Ciudade) era borrado físico.
        $this->assertNull(Ciudad::find($inactiva));
        $this->assertNotNull(DB::table('ciudades')->where('id_municipio', $inactiva)->value('deleted_at'));
    }
}
