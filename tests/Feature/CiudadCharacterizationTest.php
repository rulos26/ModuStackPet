<?php

namespace Tests\Feature;

use App\Models\Ciudad;
use App\Models\Ciudade;
use App\Models\Departamento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * SEG-034: pruebas de caracterización de Ciudad / Ciudade (misma tabla
 * `ciudades`). Describen el comportamiento ACTUAL antes de consolidar.
 */
class CiudadCharacterizationTest extends TestCase
{
    use RefreshDatabase;

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
    public function both_models_share_table_and_primary_key(): void
    {
        $this->assertSame('ciudades', (new Ciudad)->getTable());
        $this->assertSame('ciudades', (new Ciudade)->getTable());
        $this->assertSame('id_municipio', (new Ciudad)->getKeyName());
        $this->assertSame('id_municipio', (new Ciudade)->getKeyName());
    }

    #[Test]
    public function both_models_read_the_same_row_and_its_departamento(): void
    {
        $dep = $this->departamento();
        $id = $this->ciudadRow($dep);

        foreach ([Ciudad::class, Ciudade::class] as $model) {
            $c = $model::findOrFail($id);
            $this->assertSame('Bogotá', $c->municipio);
            $this->assertSame('Cundinamarca', $c->departamento->nombre);
        }
    }

    #[Test]
    public function estado_cast_differs_between_models(): void
    {
        $id = $this->ciudadRow($this->departamento());

        $this->assertSame(1, Ciudade::find($id)->estado);
        $this->assertTrue(Ciudad::find($id)->estado);
        // Los comparadores de las vistas (`== 1`) funcionan con ambos.
        $this->assertTrue(Ciudad::find($id)->estado == 1);
    }

    #[Test]
    public function ciudade_delete_is_hard_and_ciudad_delete_is_soft(): void
    {
        $dep = $this->departamento();
        $a = $this->ciudadRow($dep, 'A');
        $b = $this->ciudadRow($dep, 'B');

        Ciudade::find($a)->delete();
        $this->assertDatabaseMissing('ciudades', ['id_municipio' => $a]);

        Ciudad::find($b)->delete();
        $this->assertDatabaseHas('ciudades', ['id_municipio' => $b]);
        $this->assertNotNull(DB::table('ciudades')->where('id_municipio', $b)->value('deleted_at'));
        $this->assertNull(Ciudad::find($b));
    }

    #[Test]
    public function departamento_ciudades_returns_ciudade_instances(): void
    {
        $dep = $this->departamento();
        $this->ciudadRow($dep, 'A');
        $this->ciudadRow($dep, 'B');

        $ciudades = Departamento::find($dep)->ciudades;

        $this->assertCount(2, $ciudades);
        $this->assertContainsOnlyInstancesOf(Ciudade::class, $ciudades);
    }

    #[Test]
    public function ciudad_has_empresas_relation_ciudade_does_not(): void
    {
        $this->assertTrue(method_exists(Ciudad::class, 'empresas'));
        $this->assertFalse(method_exists(Ciudade::class, 'empresas'));
    }

    #[Test]
    public function ciudades_index_and_show_render(): void
    {
        $dep = $this->departamento();
        $id = $this->ciudadRow($dep, 'Zipaquirá');

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
    public function destroy_refuses_active_city_and_removes_inactive_one(): void
    {
        $dep = $this->departamento();
        $activa = $this->ciudadRow($dep, 'Activa', 1);
        $inactiva = $this->ciudadRow($dep, 'Inactiva', 0);

        $this->delete("/ciudades/{$activa}")->assertSessionHas('error');
        $this->assertDatabaseHas('ciudades', ['id_municipio' => $activa, 'deleted_at' => null]);

        $this->delete("/ciudades/{$inactiva}")->assertSessionHas('success');
        $this->assertNull(Ciudade::find($inactiva));
    }

    #[Test]
    public function store_current_behavior(): void
    {
        $dep = $this->departamento();

        $response = $this->post('/ciudades', [
            'municipio' => 'Nueva', 'departamento_id' => $dep, 'estado' => 1,
        ]);

        // Hoy la regla `exists:departamentos,id` apunta a una columna que no
        // existe (la PK es id_departamento): crear desde el CRUD falla siempre.
        // Bug preexistente, fuera del alcance de la consolidación (ver informe).
        $response->assertSessionHasErrors('departamento_id');
        $this->assertDatabaseMissing('ciudades', ['municipio' => 'Nueva']);
    }

    #[Test]
    public function create_edit_forms_and_update_are_broken_today(): void
    {
        // Bug preexistente: el controlador usa pluck('nombre', 'id') y la
        // validación exists:departamentos,id, pero la PK es id_departamento.
        $dep = $this->departamento();
        $id = $this->ciudadRow($dep, 'Vieja');

        $this->get('/ciudades/create')->assertStatus(500);
        $this->get("/ciudades/{$id}/edit")->assertStatus(500);

        $this->put("/ciudades/{$id}", ['municipio' => 'Nueva', 'departamento_id' => $dep, 'estado' => 1])
            ->assertSessionHasErrors('departamento_id');
        $this->assertSame('Vieja', DB::table('ciudades')->where('id_municipio', $id)->value('municipio'));
    }
}
