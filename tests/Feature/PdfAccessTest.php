<?php

namespace Tests\Feature;

use App\Models\Mascota;
use App\Models\User;
use Database\Factories\MascotaFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * SEG-042: /pdf/mascota exponía email/teléfono del propietario sin sesión;
 * empresas.pdf estaba registrada dos veces y la que ganaba solo pedía auth.
 */
class PdfAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['Superadmin', 'Admin', 'Cliente', 'Paseador'] as $role) {
            Role::create(['name' => $role]);
        }
    }

    private function userWithRole(string $role, array $attrs = []): User
    {
        $user = User::factory()->create($attrs);
        $user->assignRole($role);

        return $user;
    }

    private function mascotaDe(User $dueño): Mascota
    {
        return MascotaFactory::new()->create(['user_id' => $dueño->id]);
    }

    #[Test]
    public function guest_cannot_get_a_mascota_pdf_nor_see_owner_data(): void
    {
        $dueño = $this->userWithRole('Cliente', ['email' => 'dueno-secreto@example.test', 'telefono' => '3001234567']);
        $mascota = $this->mascotaDe($dueño);

        foreach (["/pdf/mascota/{$mascota->id}", '/pdf/mascota?mascota='.$mascota->id, '/pdf/mascota', '/pdf'] as $uri) {
            $response = $this->get($uri);
            $response->assertRedirect(route('login'));
            $this->assertStringNotContainsString('dueno-secreto@example.test', (string) $response->getContent());
        }
    }

    #[Test]
    public function owner_admin_and_superadmin_get_the_pdf(): void
    {
        $dueño = $this->userWithRole('Cliente');
        $mascota = $this->mascotaDe($dueño);

        foreach ([$dueño, $this->userWithRole('Admin'), $this->userWithRole('Superadmin')] as $user) {
            $response = $this->actingAs($user)->get("/pdf/mascota/{$mascota->id}");
            $response->assertOk();
            $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
        }
    }

    #[Test]
    public function other_cliente_and_paseador_cannot_get_someone_elses_mascota_pdf(): void
    {
        $mascota = $this->mascotaDe($this->userWithRole('Cliente'));

        $this->actingAs($this->userWithRole('Cliente'))->get("/pdf/mascota/{$mascota->id}")->assertForbidden();
        $this->actingAs($this->userWithRole('Paseador'))->get("/pdf/mascota/{$mascota->id}")->assertForbidden();
    }

    #[Test]
    public function unverified_user_is_sent_to_verification(): void
    {
        $user = User::factory()->unverified()->create();
        $user->assignRole('Cliente');
        $mascota = $this->mascotaDe($user);

        $this->actingAs($user)->get("/pdf/mascota/{$mascota->id}")->assertRedirect(route('verification.notice'));
    }

    #[Test]
    public function missing_mascota_is_404_and_no_id_redirects_to_the_list(): void
    {
        $admin = $this->userWithRole('Admin');

        $this->actingAs($admin)->get('/pdf/mascota/9999')->assertNotFound();
        $this->actingAs($admin)->get('/pdf/mascota')->assertRedirect(route('mascotas.index'));
    }

    #[Test]
    public function empresas_pdf_is_registered_once_with_auth_verified_and_role(): void
    {
        $matches = collect(Route::getRoutes()->getRoutes())
            ->filter(fn ($r) => $r->uri() === 'empresas/{empresa}/pdf');

        $this->assertCount(1, $matches);
        $middleware = $matches->first()->gatherMiddleware();
        $this->assertContains('auth', $middleware);
        $this->assertContains('verified', $middleware);
        $this->assertContains('role:Superadmin|Admin', $middleware);
    }

    #[Test]
    public function empresas_pdf_requires_login_verification_and_role(): void
    {
        $this->get('/empresas/1/pdf')->assertRedirect(route('login'));

        $this->actingAs($this->userWithRole('Cliente'))->get('/empresas/1/pdf')->assertForbidden();
        $this->actingAs($this->userWithRole('Paseador'))->get('/empresas/1/pdf')->assertForbidden();

        $sinVerificar = User::factory()->unverified()->create();
        $sinVerificar->assignRole('Admin');
        $this->actingAs($sinVerificar)->get('/empresas/1/pdf')->assertRedirect(route('verification.notice'));
    }

    #[Test]
    public function admin_can_download_an_empresa_pdf(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');
        $id = DB::table('empresas')->insertGetId([
            'nombre_legal' => 'Acme SAS', 'nit' => '900123456', 'representante_legal' => 'Ana',
            'tipo_empresa_id' => 1, 'ciudad_id' => 1, 'departamento_id' => 1, 'sector_id' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->userWithRole('Admin'))->get("/empresas/{$id}/pdf");

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }
}
