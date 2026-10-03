<?php

namespace Tests\Feature;

use App\Models\Sector;
use App\Models\Sectore;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * SEG-035: caracterización de Sector / Sectore (misma tabla `sectores`)
 * y del CRUD /sectores antes de consolidar.
 */
class SectorCharacterizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create();
        $admin->assignRole(Role::create(['name' => 'Admin']));
        $this->actingAs($admin);
    }

    private function row(string $nombre = 'Salud'): int
    {
        return DB::table('sectores')->insertGetId(['nombre' => $nombre, 'created_at' => now(), 'updated_at' => now()]);
    }

    #[Test]
    public function both_models_share_the_table_and_read_the_same_row(): void
    {
        $id = $this->row();

        $this->assertSame('sectores', (new Sector)->getTable());
        $this->assertSame('sectores', (new Sectore)->getTable());
        $this->assertSame('Salud', Sector::find($id)->nombre);
        $this->assertSame('Salud', Sectore::find($id)->nombre);
    }

    #[Test]
    public function delete_is_hard_for_sectore_and_soft_for_sector(): void
    {
        $a = $this->row('A');
        $b = $this->row('B');

        Sectore::find($a)->delete();
        $this->assertDatabaseMissing('sectores', ['id' => $a]);

        Sector::find($b)->delete();
        $this->assertNotNull(DB::table('sectores')->where('id', $b)->value('deleted_at'));
    }

    #[Test]
    public function sector_has_empresas_relation_sectore_does_not(): void
    {
        $this->assertTrue(method_exists(Sector::class, 'empresas'));
        $this->assertFalse(method_exists(Sectore::class, 'empresas'));
    }

    #[Test]
    public function index_lists_twenty_per_page(): void
    {
        for ($i = 1; $i <= 21; $i++) {
            $this->row(sprintf('Sector %02d', $i));
        }

        $this->get('/sectores')->assertOk()->assertSee('Sector 20')->assertDontSee('Sector 21');
    }

    #[Test]
    public function crud_works_end_to_end(): void
    {
        $this->post('/sectores', ['nombre' => 'Tecnología'])->assertRedirect(route('sectores.index'));
        $id = DB::table('sectores')->where('nombre', 'Tecnología')->value('id');
        $this->assertNotNull($id);

        $this->get("/sectores/{$id}")->assertOk(); // la vista show hoy no imprime el nombre
        $this->get("/sectores/{$id}/edit")->assertOk();

        $this->put("/sectores/{$id}", ['nombre' => 'Tecnología 2'])->assertRedirect(route('sectores.index'));
        $this->assertSame('Tecnología 2', DB::table('sectores')->where('id', $id)->value('nombre'));

        // Puede conservar su propio nombre, pero no repetir el de otro.
        $otro = $this->row('Otro');
        $this->put("/sectores/{$id}", ['nombre' => 'Tecnología 2'])->assertSessionHasNoErrors();
        $this->put("/sectores/{$id}", ['nombre' => 'Otro'])->assertSessionHasErrors('nombre');
        $this->post('/sectores', ['nombre' => 'Otro'])->assertSessionHasErrors('nombre');

        $this->delete("/sectores/{$id}")->assertRedirect(route('sectores.index'));
        $this->assertNull(Sectore::find($id));
        $this->assertNotNull($otro);
    }

    #[Test]
    public function missing_id_currently_crashes(): void
    {
        // Bug preexistente: find() sin fail → null: show renderiza vacío (200), edit y destroy dan 500.
        $this->get('/sectores/9999')->assertOk(); // la vista show es tolerante a null
        $this->get('/sectores/9999/edit')->assertStatus(500);
        $this->delete('/sectores/9999')->assertStatus(500);
    }

    #[Test]
    public function routes_already_require_auth_and_role(): void
    {
        // Cubierto por 040; se fija aquí para que 035 no lo regrese.
        auth()->logout();
        $this->get('/sectores')->assertRedirect(route('login'));
    }
}
