<?php

namespace Tests\Feature;

use App\Models\Mascota;
use App\Models\MensajeDeBienvenida;
use App\Models\Raza;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Red de seguridad de renderizado antes de migrar el frontend.
 *
 * Las aserciones comprueban contenido estable y evitan depender de clases CSS
 * o de la estructura HTML que cambiará durante la migración.
 */
class FrontendViewRenderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['Superadmin', 'Admin', 'Cliente', 'Paseador'] as $role) {
            Role::create(['name' => $role]);
            MensajeDeBienvenida::create([
                'rol' => $role,
                'titulo' => "Bienvenido {$role}",
                'descripcion' => "Panel de {$role}",
                'logo' => 'storage/img/logo.jpg',
            ]);
        }
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create(['name' => "Usuario {$role}"]);
        $user->assignRole($role);

        return $user;
    }

    #[Test]
    public function superadmin_views_render(): void
    {
        $user = $this->userWithRole('Superadmin');

        $this->actingAs($user)->get(route('superadmin.dashboard'))
            ->assertOk()
            ->assertSeeText('Bienvenido Superadmin');

        $this->get(route('superadmin.usuarios.index'))
            ->assertOk()
            ->assertSeeText('Gestión de Usuarios');

        $this->get(route('superadmin.usuarios.create'))
            ->assertOk()
            ->assertSeeText('Crear Nuevo Usuario');
    }

    #[Test]
    public function admin_views_render(): void
    {
        $user = $this->userWithRole('Admin');

        $this->actingAs($user)->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSeeText('Bienvenido Admin');

        $this->get(route('admin.users.index'))
            ->assertOk()
            ->assertSeeText('Gestión de Usuarios');

        $this->get(route('admin.users.show', $user))
            ->assertOk()
            ->assertSeeText('Detalles del Usuario');

        $this->get(route('admin.users.edit', $user))
            ->assertOk()
            ->assertSeeText('Editar Usuario');
    }

    #[Test]
    public function cliente_views_render_including_pet_list_form_and_detail(): void
    {
        $user = $this->userWithRole('Cliente');
        $raza = Raza::create(['tipo_mascota' => 'Perro', 'nombre' => 'Criollo Render']);
        $mascota = Mascota::create([
            'user_id' => $user->id,
            'nombre' => 'Firulais Render',
            'edad' => 4,
            'raza_id' => $raza->id,
            'genero' => 'Macho',
            'vacunas_completas' => true,
            'esterilizado' => true,
        ]);

        $this->actingAs($user)->get(route('cliente.dashboard'))
            ->assertOk()
            ->assertSeeText($user->name);

        $this->get(route('mascotas.index'))
            ->assertOk()
            ->assertSeeText('Gestión de Mascotas');

        $this->get(route('mascotas.create'))
            ->assertOk()
            ->assertSeeText('Crear Mascota');

        $this->get(route('mascotas.show', $mascota))
            ->assertOk()
            ->assertSeeText('Firulais Render');
    }

    #[Test]
    public function paseador_views_render(): void
    {
        $user = $this->userWithRole('Paseador');

        $this->actingAs($user)->get(route('paseador.dashboard'))
            ->assertOk()
            ->assertSeeText('Bienvenido Paseador');

        $this->get(route('paseador.perfil.index'))
            ->assertOk()
            ->assertSeeText('Gestión de Usuarios');

        $this->get(route('paseador.perfil.show', $user))
            ->assertOk()
            ->assertSeeText($user->name);

        $this->get(route('paseador.perfil.edit', $user))
            ->assertOk()
            ->assertSeeText('Actualizar Usuario');
    }

    #[Test]
    public function guest_authentication_views_render(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSeeText('Iniciar Sesión');

        $this->get(route('register'))
            ->assertOk()
            ->assertSeeText('Registrarse');

        $this->get(route('password.request'))
            ->assertOk()
            ->assertSeeText('Olvidé mi contraseña');

        $this->get(route('password.reset', [
            'token' => 'token-de-render',
            'email' => 'invitado@example.test',
        ]))
            ->assertOk()
            ->assertSeeText('Restablecer Contraseña');
    }
}
