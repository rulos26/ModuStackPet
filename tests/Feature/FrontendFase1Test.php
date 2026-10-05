<?php

namespace Tests\Feature;

use App\Models\MensajeDeBienvenida;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * SEG-045 (Fase 1 parcial del informe unificado de frontend): FE-05, FE-07,
 * FE-08, FE-13 y FE-21.
 */
class FrontendFase1Test extends TestCase
{
    use RefreshDatabase;

    /** @return list<string> rutas relativas de todas las vistas Blade */
    private function vistas(): array
    {
        return collect(File::allFiles(resource_path('views')))
            ->filter(fn ($f) => str_ends_with($f->getFilename(), '.blade.php'))
            ->map(fn ($f) => str_replace('\\', '/', $f->getRelativePathname()))
            ->values()
            ->all();
    }

    private function contenido(string $vista): string
    {
        return file_get_contents(resource_path('views/'.$vista));
    }

    #[Test]
    public function ninguna_vista_contiene_console_log(): void
    {
        $con = array_values(array_filter(
            $this->vistas(),
            fn ($v) => str_contains($this->contenido($v), 'console.log')
        ));

        $this->assertSame([], $con, 'Vistas con console.log: '.implode(', ', $con));
    }

    #[Test]
    public function el_login_no_registra_datos_del_formulario_en_consola(): void
    {
        $html = $this->get('/login')->assertOk()->getContent();

        $this->assertStringNotContainsString('console.', $html);
        $this->assertStringNotContainsString('passwordLength', $html);
    }

    #[Test]
    public function las_rutas_de_imagen_no_llevan_el_prefijo_public(): void
    {
        // layouts/navbar y layouts/sidebar los reescribe la Fase 2 (no se tocan aquí).
        $pendientesFase2 = ['layouts/navbar.blade.php', 'layouts/sidebar.blade.php'];

        $con = array_values(array_filter(
            $this->vistas(),
            fn ($v) => ! in_array($v, $pendientesFase2, true)
                && preg_match('/asset\(\s*[\'"]public\//', $this->contenido($v)) === 1
        ));

        $this->assertSame([], $con, "asset('public/…') da 404; vistas: ".implode(', ', $con));
    }

    #[Test]
    public function el_logo_del_login_apunta_a_una_url_que_existe(): void
    {
        $html = $this->get('/login')->assertOk()->getContent();

        $this->assertStringContainsString('/storage/img/logo.jpg', $html);
        $this->assertStringNotContainsString('/public/storage/', $html);
        $this->assertFileExists(public_path('storage/img/logo.jpg'));
    }

    #[Test]
    public function forgot_password_esta_en_espanol_con_estilos_y_main(): void
    {
        $html = $this->get('/forgot-password')->assertOk()->getContent();

        $this->assertStringContainsString('<html lang="es">', $html);
        $this->assertStringContainsString('bootstrap', $html);
        $this->assertStringContainsString('<main', $html);
        $this->assertStringContainsString('autocomplete="email"', $html);
        $this->assertSame(1, substr_count($html, '<h1'));
    }

    #[Test]
    public function forgot_password_muestra_el_estado_de_exito_y_los_errores(): void
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'existe@example.test']);

        $ok = $this->followingRedirects()->from('/forgot-password')
            ->post('/forgot-password', ['email' => $user->email]);
        $ok->assertSee('role="status"', false);

        $error = $this->followingRedirects()->from('/forgot-password')
            ->post('/forgot-password', ['email' => 'no-existe@example.test']);
        $error->assertSee('role="alert"', false)->assertSee('is-invalid', false);
        $this->assertStringNotContainsString("We can't find", $error->getContent());
    }

    public static function dashboardsProvider(): array
    {
        return [
            'Superadmin' => ['Superadmin', 'superadmin.dashboard'],
            'Admin' => ['Admin', 'admin.dashboard'],
            // Un Cliente con el perfil incompleto ve 'Completa tu Perfil' (otra vista hija con el mismo contrato de <h1>).
            'Cliente' => ['Cliente', 'cliente.dashboard'],
            'Paseador' => ['Paseador', 'paseador.dashboard'],
        ];
    }

    #[Test]
    #[DataProvider('dashboardsProvider')]
    public function el_dashboard_tiene_un_unico_h1_con_titulo_y_sin_rn_literales(string $rol, string $ruta): void
    {
        Role::create(['name' => $rol]);
        // Secuencias literales (backslash + r + backslash + n) como las guarda el dato real.
        MensajeDeBienvenida::create([
            'titulo' => 'Bienvenido de prueba',
            'descripcion' => 'Primera frase.\r\n\r\nSegunda frase. \n Tercera frase.',
            'rol' => $rol,
        ]);
        $user = User::factory()->create();
        $user->assignRole($rol);

        $html = $this->actingAs($user)->get(route($ruta))->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '<h1'), "[$rol] debe haber un único <h1>");
        $this->assertMatchesRegularExpression('#<h1[^>]*>\s*\S[^<]*</h1>#u', $html, "[$rol] <h1> vacío");
        $this->assertMatchesRegularExpression('#<title>\s*(Dashboard|Completa tu Perfil)#u', $html);

        $this->assertStringNotContainsString('\r\n', $html);
        $this->assertStringNotContainsString('\n ', $html);
        if (! str_contains($html, 'Completa tu Perfil')) {
            $this->assertStringContainsString('Segunda frase', $html);
        }
    }

    #[Test]
    public function la_vista_verify_email_tiene_titulo(): void
    {
        $user = User::factory()->unverified()->create();

        $html = $this->actingAs($user)->get(route('verification.notice'))->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertMatchesRegularExpression('#<h1[^>]*>\s*Verifica tu correo#u', $html);
    }
}
