<?php

namespace Tests\Feature;

use App\Models\DocumentRequirement;
use App\Models\User;
use Database\Factories\MascotaFactory;
use Database\Factories\RazaFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RazaCuidadosEspecialesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['Superadmin', 'Cliente'] as $role) {
            Role::create(['name' => $role]);
        }
    }

    #[Test]
    public function mascota_de_raza_marcada_muestra_advertencia_y_cuidados_en_su_ficha(): void
    {
        $cliente = $this->userWithRole('Cliente');
        $raza = RazaFactory::new()->create([
            'requiere_cuidado_especial' => true,
            'cuidados_especiales' => 'Usar bozal y mantener la correa corta.',
        ]);
        $mascota = MascotaFactory::new()->create([
            'user_id' => $cliente->id,
            'raza_id' => $raza->id,
        ]);

        $this->actingAs($cliente)
            ->get(route('mascotas.show', $mascota))
            ->assertOk()
            ->assertSeeText('Cuidado especial requerido')
            ->assertSeeText('Usar bozal y mantener la correa corta.');
    }

    #[Test]
    public function mascota_de_raza_sin_marca_no_muestra_advertencia_de_cuidado_especial(): void
    {
        $cliente = $this->userWithRole('Cliente');
        $raza = RazaFactory::new()->create([
            'requiere_cuidado_especial' => false,
            'cuidados_especiales' => null,
        ]);
        $mascota = MascotaFactory::new()->create([
            'user_id' => $cliente->id,
            'raza_id' => $raza->id,
        ]);

        $this->actingAs($cliente)
            ->get(route('mascotas.show', $mascota))
            ->assertOk()
            ->assertDontSeeText('Cuidado especial requerido');
    }

    #[Test]
    public function superadmin_puede_configurar_los_cuidados_especiales_desde_el_panel_de_razas(): void
    {
        $superadmin = $this->userWithRole('Superadmin');

        $this->actingAs($superadmin)
            ->post(route('razas.store'), [
                'tipo_mascota' => 'Perro',
                'nombre' => 'Raza configurada por humano',
                'requiere_cuidado_especial' => '1',
                'cuidados_especiales' => 'Usar arnés de doble sujeción.',
            ])
            ->assertRedirect(route('razas.index'));

        $this->assertDatabaseHas('razas', [
            'nombre' => 'Raza configurada por humano',
            'requiere_cuidado_especial' => true,
            'cuidados_especiales' => 'Usar arnés de doble sujeción.',
        ]);
    }

    #[Test]
    public function requisito_limitado_aplica_solo_a_razas_con_cuidado_especial(): void
    {
        $requirement = new DocumentRequirement(['aplica_razas_peligrosas' => true]);
        $razaMarcada = RazaFactory::new()->make(['requiere_cuidado_especial' => true]);
        $razaSinMarca = RazaFactory::new()->make(['requiere_cuidado_especial' => false]);

        $this->assertTrue($requirement->aplicaParaRaza($razaMarcada));
        $this->assertFalse($requirement->aplicaParaRaza($razaSinMarca));
        $this->assertFalse($requirement->aplicaParaRaza(null));
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}

