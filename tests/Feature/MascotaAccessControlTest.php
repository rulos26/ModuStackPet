<?php

namespace Tests\Feature;

use App\Models\Mascota;
use App\Models\User;
use Database\Factories\MascotaFactory;
use Database\Factories\RazaFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Reproduce y corrige el defecto 1 (IDOR crítico) documentado en
 * docs/auditorias/pruebas-mascotas-documentos.md: las rutas mascotas.* no
 * exigían auth/verified, y show/edit/update/destroy no comprobaban dueño ni
 * rol.
 */
class MascotaAccessControlTest extends TestCase
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
    public function guest_is_redirected_to_login_on_every_mascota_route(): void
    {
        $mascota = MascotaFactory::new()->create();

        $this->get(route('mascotas.index'))->assertRedirect(route('login'));
        $this->get(route('mascotas.create'))->assertRedirect(route('login'));
        $this->get(route('mascotas.show', $mascota))->assertRedirect(route('login'));
        $this->get(route('mascotas.edit', $mascota))->assertRedirect(route('login'));
        $this->put(route('mascotas.update', $mascota), [])->assertRedirect(route('login'));
        $this->delete(route('mascotas.destroy', $mascota))->assertRedirect(route('login'));

        $this->assertDatabaseHas('mascotas', ['id' => $mascota->id]);
    }

    #[Test]
    public function cliente_ajeno_no_puede_ver_editar_actualizar_ni_borrar_mascota_de_otro(): void
    {
        $owner = $this->userWithRole('Cliente');
        $attacker = $this->userWithRole('Cliente');
        $raza = RazaFactory::new()->create();
        $mascota = MascotaFactory::new()->create(['user_id' => $owner->id, 'raza_id' => $raza->id]);

        $validData = [
            'nombre' => 'Secuestrada',
            'edad' => 3,
            'raza_id' => $raza->id,
            'genero' => 'Macho',
            'vacunas_completas' => '1',
            'esterilizado' => '0',
        ];

        $this->actingAs($attacker)->get(route('mascotas.show', $mascota))->assertForbidden();
        $this->actingAs($attacker)->get(route('mascotas.edit', $mascota))->assertForbidden();
        $this->actingAs($attacker)->put(route('mascotas.update', $mascota), $validData)->assertForbidden();
        $this->actingAs($attacker)->delete(route('mascotas.destroy', $mascota))->assertForbidden();

        $this->assertDatabaseHas('mascotas', [
            'id' => $mascota->id,
            'user_id' => $owner->id,
            'nombre' => $mascota->nombre,
        ]);
    }

    #[Test]
    public function paseador_no_tiene_acceso_a_mascotas_de_otros(): void
    {
        $owner = $this->userWithRole('Cliente');
        $paseador = $this->userWithRole('Paseador');
        $raza = RazaFactory::new()->create();
        $mascota = MascotaFactory::new()->create(['user_id' => $owner->id, 'raza_id' => $raza->id]);

        $this->actingAs($paseador)->get(route('mascotas.show', $mascota))->assertForbidden();
        $this->actingAs($paseador)->get(route('mascotas.edit', $mascota))->assertForbidden();
        $this->actingAs($paseador)->put(route('mascotas.update', $mascota), [
            'nombre' => 'Robada',
            'edad' => 1,
            'raza_id' => $raza->id,
            'genero' => 'Hembra',
            'vacunas_completas' => '0',
            'esterilizado' => '0',
        ])->assertForbidden();
        $this->actingAs($paseador)->delete(route('mascotas.destroy', $mascota))->assertForbidden();

        $this->assertDatabaseHas('mascotas', ['id' => $mascota->id, 'user_id' => $owner->id]);
    }

    #[Test]
    public function admin_y_superadmin_pueden_ver_editar_y_borrar_mascotas_de_cualquiera(): void
    {
        $owner = $this->userWithRole('Cliente');
        $raza = RazaFactory::new()->create();

        foreach (['Admin', 'Superadmin'] as $role) {
            $staff = $this->userWithRole($role);
            $mascota = MascotaFactory::new()->create(['user_id' => $owner->id, 'raza_id' => $raza->id]);

            $this->actingAs($staff)->get(route('mascotas.show', $mascota))->assertOk();
            $this->actingAs($staff)->get(route('mascotas.edit', $mascota))->assertOk();

            $this->actingAs($staff)->put(route('mascotas.update', $mascota), [
                'nombre' => 'Actualizada por ' . $role,
                'edad' => 5,
                'raza_id' => $raza->id,
                'genero' => 'Macho',
                'vacunas_completas' => '1',
                'esterilizado' => '1',
            ])->assertRedirect(route('mascotas.index'));

            $this->assertDatabaseHas('mascotas', [
                'id' => $mascota->id,
                'nombre' => 'Actualizada por ' . $role,
            ]);

            $this->actingAs($staff)->delete(route('mascotas.destroy', $mascota))->assertRedirect(route('mascotas.index'));
            $this->assertDatabaseMissing('mascotas', ['id' => $mascota->id]);
        }
    }

    #[Test]
    public function nadie_puede_cambiar_el_dueno_al_editar_ni_siquiera_admin_o_superadmin(): void
    {
        $owner = $this->userWithRole('Cliente');
        $raza = RazaFactory::new()->create();

        foreach (['Admin', 'Superadmin'] as $role) {
            $staff = $this->userWithRole($role);
            $mascota = MascotaFactory::new()->create(['user_id' => $owner->id, 'raza_id' => $raza->id]);

            $this->actingAs($staff)->put(route('mascotas.update', $mascota), [
                'nombre' => 'Con nuevo dueno',
                'edad' => 2,
                'raza_id' => $raza->id,
                'genero' => 'Hembra',
                'vacunas_completas' => '0',
                'esterilizado' => '0',
                'user_id' => $staff->id,
            ]);

            $this->assertDatabaseHas('mascotas', [
                'id' => $mascota->id,
                'user_id' => $owner->id,
            ]);
        }

        $cliente = $this->userWithRole('Cliente');
        $mascotaPropia = MascotaFactory::new()->create(['user_id' => $cliente->id, 'raza_id' => $raza->id]);
        $otro = $this->userWithRole('Cliente');

        $this->actingAs($cliente)->put(route('mascotas.update', $mascotaPropia), [
            'nombre' => 'Intento apropiacion',
            'edad' => 2,
            'raza_id' => $raza->id,
            'genero' => 'Hembra',
            'vacunas_completas' => '0',
            'esterilizado' => '0',
            'user_id' => $otro->id,
        ]);

        $this->assertDatabaseHas('mascotas', [
            'id' => $mascotaPropia->id,
            'user_id' => $cliente->id,
        ]);
    }

    #[Test]
    public function mascota_controller_no_expone_el_mensaje_interno_de_la_excepcion(): void
    {
        $cliente = $this->userWithRole('Cliente');
        $raza = RazaFactory::new()->create();
        $mascota = MascotaFactory::new()->create(['user_id' => $cliente->id, 'raza_id' => $raza->id]);

        // Forzar una excepción en destroy() borrando la mascota bajo el controlador
        // (avatar path inexistente ya se maneja bien; aquí verificamos que si algo
        // falla, el mensaje mostrado al usuario nunca contiene texto de la excepción
        // real). Como no podemos forzar fácilmente una excepción real sin tocar el
        // storage, verificamos directamente que el código fuente ya no interpola
        // $e->getMessage() en los mensajes de sesión.
        $this->assertStringNotContainsString(
            '$e->getMessage()',
            file_get_contents(app_path('Http/Controllers/MascotaController.php'))
        );
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
