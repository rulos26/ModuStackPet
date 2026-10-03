<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VacunasCertificacione;
use Database\Factories\MascotaFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VacunasCertificacionesFlowsTest extends TestCase
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
    public function invitado_es_redirigido_en_todas_las_rutas_del_recurso(): void
    {
        $owner = $this->userWithRole('Cliente');
        $record = $this->recordFor($owner);

        $requests = [
            fn () => $this->get(route('vacunas_certificaciones.index')),
            fn () => $this->get(route('vacunas_certificaciones.create')),
            fn () => $this->post(route('vacunas_certificaciones.store'), []),
            fn () => $this->get(route('vacunas_certificaciones.show', $record)),
            fn () => $this->get(route('vacunas_certificaciones.edit', $record)),
            fn () => $this->put(route('vacunas_certificaciones.update', $record), []),
            fn () => $this->delete(route('vacunas_certificaciones.destroy', $record)),
        ];

        foreach ($requests as $request) {
            $request()->assertRedirect(route('login'));
        }

        $this->assertDatabaseHas('vacunas_certificaciones', ['id' => $record->id]);
    }

    #[Test]
    public function cliente_lista_y_selecciona_solo_sus_propias_mascotas(): void
    {
        $cliente = $this->userWithRole('Cliente');
        $otro = $this->userWithRole('Cliente');
        $ownRecord = $this->recordFor($cliente, 'Vacuna propia');
        $foreignRecord = $this->recordFor($otro, 'Vacuna ajena');

        $this->actingAs($cliente)
            ->get(route('vacunas_certificaciones.index'))
            ->assertOk()
            ->assertSeeText($ownRecord->mascota->nombre)
            ->assertDontSeeText($foreignRecord->mascota->nombre);

        $this->actingAs($cliente)
            ->get(route('vacunas_certificaciones.create'))
            ->assertOk()
            ->assertSeeText($ownRecord->mascota->nombre)
            ->assertDontSeeText($foreignRecord->mascota->nombre);
    }

    #[Test]
    public function admin_lista_registros_y_mascotas_de_todos_los_propietarios(): void
    {
        $admin = $this->userWithRole('Admin');
        $first = $this->recordFor($this->userWithRole('Cliente'), 'Mascota uno');
        $second = $this->recordFor($this->userWithRole('Cliente'), 'Mascota dos');

        $this->actingAs($admin)
            ->get(route('vacunas_certificaciones.index'))
            ->assertOk()
            ->assertSeeText($first->mascota->nombre)
            ->assertSeeText($second->mascota->nombre);

        $this->actingAs($admin)
            ->get(route('vacunas_certificaciones.create'))
            ->assertOk()
            ->assertSeeText($first->mascota->nombre)
            ->assertSeeText($second->mascota->nombre);
    }

    #[Test]
    public function cliente_crea_un_registro_valido_y_validacion_rechaza_datos_incompletos(): void
    {
        $cliente = $this->userWithRole('Cliente');
        $mascota = MascotaFactory::new()->create([
            'user_id' => $cliente->id,
            'nombre' => 'Luna vacunada',
        ]);

        $this->actingAs($cliente)
            ->post(route('vacunas_certificaciones.store'), [
                'id_mascota' => $mascota->id,
                'fecha_ultima_vacuna' => now()->subDay()->toDateString(),
                'operaciones' => 'Esterilización registrada',
            ])
            ->assertRedirect(route('vacunas_certificaciones.index'));

        $this->assertDatabaseHas('vacunas_certificaciones', [
            'id_mascota' => $mascota->id,
            'operaciones' => 'Esterilización registrada',
        ]);

        $this->actingAs($cliente)
            ->from(route('vacunas_certificaciones.create'))
            ->post(route('vacunas_certificaciones.store'), [])
            ->assertRedirect(route('vacunas_certificaciones.create'))
            ->assertSessionHasErrors(['id_mascota', 'fecha_ultima_vacuna']);
    }

    #[Test]
    public function cliente_no_puede_crear_registro_para_mascota_ajena(): void
    {
        $cliente = $this->userWithRole('Cliente');
        $mascotaAjena = MascotaFactory::new()->create([
            'user_id' => $this->userWithRole('Cliente')->id,
        ]);

        $this->actingAs($cliente)
            ->post(route('vacunas_certificaciones.store'), [
                'id_mascota' => $mascotaAjena->id,
                'fecha_ultima_vacuna' => now()->subDay()->toDateString(),
            ])
            ->assertRedirect(route('vacunas_certificaciones.create'))
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('vacunas_certificaciones', [
            'id_mascota' => $mascotaAjena->id,
        ]);
    }

    #[Test]
    public function propietario_puede_ver_editar_actualizar_y_eliminar_su_registro(): void
    {
        $cliente = $this->userWithRole('Cliente');
        $record = $this->recordFor($cliente, 'Mascota propia');

        $this->actingAs($cliente)
            ->get(route('vacunas_certificaciones.show', $record))
            ->assertOk()
            ->assertSeeText('Mascota propia');

        $this->actingAs($cliente)
            ->get(route('vacunas_certificaciones.edit', $record))
            ->assertOk()
            ->assertSeeText('Mascota propia');

        $this->actingAs($cliente)
            ->put(route('vacunas_certificaciones.update', $record), [
                'id_mascota' => $record->id_mascota,
                'fecha_ultima_vacuna' => now()->subDays(2)->toDateString(),
                'operaciones' => 'Control actualizado',
            ])
            ->assertRedirect(route('vacunas_certificaciones.index'));

        $this->assertDatabaseHas('vacunas_certificaciones', [
            'id' => $record->id,
            'operaciones' => 'Control actualizado',
        ]);

        $this->actingAs($cliente)
            ->delete(route('vacunas_certificaciones.destroy', $record))
            ->assertRedirect(route('vacunas_certificaciones.index'));

        $this->assertDatabaseMissing('vacunas_certificaciones', ['id' => $record->id]);
    }

    #[Test]
    public function tercero_no_puede_ver_editar_actualizar_ni_eliminar_registro_ajeno(): void
    {
        $tercero = $this->userWithRole('Cliente');
        $record = $this->recordFor($this->userWithRole('Cliente'));
        $payload = [
            'id_mascota' => $record->id_mascota,
            'fecha_ultima_vacuna' => now()->subDay()->toDateString(),
            'operaciones' => 'Cambio no autorizado',
        ];

        $this->actingAs($tercero)
            ->get(route('vacunas_certificaciones.show', $record))
            ->assertForbidden();
        $this->actingAs($tercero)
            ->get(route('vacunas_certificaciones.edit', $record))
            ->assertForbidden();
        $this->actingAs($tercero)
            ->put(route('vacunas_certificaciones.update', $record), $payload)
            ->assertForbidden();
        $this->actingAs($tercero)
            ->delete(route('vacunas_certificaciones.destroy', $record))
            ->assertForbidden();

        $this->assertDatabaseHas('vacunas_certificaciones', [
            'id' => $record->id,
            'operaciones' => $record->operaciones,
        ]);
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    private function recordFor(User $owner, string $petName = 'Mascota vacunada'): VacunasCertificacione
    {
        $mascota = MascotaFactory::new()->create([
            'user_id' => $owner->id,
            'nombre' => $petName,
        ]);

        return VacunasCertificacione::create([
            'id_mascota' => $mascota->id,
            'fecha_ultima_vacuna' => now()->subWeek()->toDateString(),
            'operaciones' => 'Control inicial',
        ])->load('mascota');
    }
}

