<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\MascotaFactory;
use Database\Factories\RazaFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MascotaFlowsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['Superadmin', 'Admin', 'Cliente', 'Paseador'] as $role) {
            Role::create(['name' => $role]);
        }
    }

    #[Test]
    public function cliente_can_create_view_edit_and_list_own_mascotas(): void
    {
        $cliente = $this->userWithRole('Cliente');
        $raza = RazaFactory::new()->create(['nombre' => 'Labrador']);

        $createResponse = $this->actingAs($cliente)->post(route('mascotas.store'), [
            'nombre' => 'Luna',
            'edad' => 4,
            'raza_id' => $raza->id,
            'genero' => 'Hembra',
            'vacunas_completas' => '1',
            'esterilizado' => '0',
            'comportamiento' => 'Tranquila',
        ]);

        $createResponse->assertRedirect(route('mascotas.index'));
        $this->assertDatabaseHas('mascotas', [
            'user_id' => $cliente->id,
            'nombre' => 'Luna',
        ]);

        $mascota = MascotaFactory::new()->create([
            'user_id' => $cliente->id,
            'raza_id' => $raza->id,
            'nombre' => 'Rocky',
        ]);

        $this->actingAs($cliente)
            ->get(route('mascotas.index'))
            ->assertOk()
            ->assertSee('Luna')
            ->assertSee('Rocky');

        $this->actingAs($cliente)
            ->get(route('mascotas.show', $mascota))
            ->assertOk()
            ->assertSee('Rocky');

        $this->actingAs($cliente)
            ->get(route('mascotas.edit', $mascota))
            ->assertOk();

        $updateResponse = $this->actingAs($cliente)->put(route('mascotas.update', $mascota), [
            'nombre' => 'Rocky actualizado',
            'edad' => 5,
            'raza_id' => $raza->id,
            'genero' => 'Macho',
            'vacunas_completas' => '1',
            'esterilizado' => '1',
        ]);

        $updateResponse->assertRedirect(route('mascotas.index'));
        $this->assertDatabaseHas('mascotas', [
            'id' => $mascota->id,
            'user_id' => $cliente->id,
            'nombre' => 'Rocky actualizado',
            'edad' => 5,
        ]);
    }

    #[Test]
    public function cliente_list_does_not_include_another_clientes_mascotas(): void
    {
        $cliente = $this->userWithRole('Cliente');
        $otroCliente = $this->userWithRole('Cliente');
        MascotaFactory::new()->create([
            'user_id' => $cliente->id,
            'nombre' => 'Mascota propia',
        ]);
        MascotaFactory::new()->create([
            'user_id' => $otroCliente->id,
            'nombre' => 'Mascota ajena secreta',
        ]);

        $this->actingAs($cliente)
            ->get(route('mascotas.index'))
            ->assertOk()
            ->assertSee('Mascota propia')
            ->assertDontSee('Mascota ajena secreta');
    }

    #[Test]
    public function mascota_validation_rejects_missing_required_fields(): void
    {
        $cliente = $this->userWithRole('Cliente');

        $response = $this->actingAs($cliente)
            ->from(route('mascotas.create'))
            ->post(route('mascotas.store'), ['nombre' => 'Incompleta']);

        $response->assertRedirect(route('mascotas.create'));
        $response->assertSessionHasErrors([
            'vacunas_completas',
            'esterilizado',
            'edad',
            'genero',
            'raza_id',
        ]);
        $this->assertDatabaseMissing('mascotas', ['nombre' => 'Incompleta']);
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
