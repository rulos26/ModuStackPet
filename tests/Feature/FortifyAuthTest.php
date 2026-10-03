<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * SEG-031: Fortify es el stack oficial de autenticación (registro, login y
 * reset de contraseña). 2FA queda apagado.
 */
class FortifyAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['Superadmin', 'Admin', 'Cliente', 'Paseador'] as $role) {
            Role::create(['name' => $role]);
        }
    }

    public static function fortifyRoutesProvider(): array
    {
        return [
            'register GET' => ['GET', '/register'],
            'register POST' => ['POST', '/register'],
            'login POST' => ['POST', '/login'],
            'forgot-password GET' => ['GET', '/forgot-password'],
            'forgot-password POST' => ['POST', '/forgot-password'],
            'reset-password POST' => ['POST', '/reset-password'],
        ];
    }

    #[Test]
    #[DataProvider('fortifyRoutesProvider')]
    public function auth_routes_are_served_by_fortify(string $method, string $uri): void
    {
        $route = app('router')->getRoutes()->match(
            Request::create($uri, $method)
        );

        $this->assertStringStartsWith('Laravel' . chr(92) . 'Fortify' . chr(92), ltrim($route->getActionName(), chr(92)));
    }

    #[Test]
    public function two_factor_is_disabled(): void
    {
        $this->assertFalse(Route::has('two-factor.enable'));
        $this->assertFalse(Route::has('two-factor.login'));
    }

    #[Test]
    public function auth_forms_render(): void
    {
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
        $this->get('/forgot-password')->assertOk();
        $this->get('/reset-password/token-x?email=a@b.co')->assertOk();
    }

    #[Test]
    public function registration_creates_cliente_with_role_profile_and_verification_mail(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'Ana Prueba',
            'email' => 'ana@example.test',
            'password' => 'Str0ng-Passw0rd!x',
            'password_confirmation' => 'Str0ng-Passw0rd!x',
        ]);

        $response->assertRedirect(route('cliente.dashboard'));
        $user = User::where('email', 'ana@example.test')->firstOrFail();
        $this->assertTrue($user->hasRole('Cliente'));
        $this->assertTrue((bool) $user->activo);
        $this->assertDatabaseHas('clientes', ['user_id' => $user->id]);
        $this->assertAuthenticatedAs($user);
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    #[Test]
    public function registration_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'dup@example.test']);

        $this->from('/register')->post('/register', [
            'name' => 'Dup',
            'email' => 'dup@example.test',
            'password' => 'Str0ng-Passw0rd!x',
            'password_confirmation' => 'Str0ng-Passw0rd!x',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public static function roleRedirectProvider(): array
    {
        return [
            'Superadmin' => ['Superadmin', 'superadmin.dashboard'],
            'Admin' => ['Admin', 'admin.dashboard'],
            'Cliente' => ['Cliente', 'cliente.dashboard'],
            'Paseador' => ['Paseador', 'paseador.dashboard'],
        ];
    }

    #[Test]
    #[DataProvider('roleRedirectProvider')]
    public function login_redirects_by_role(string $role, string $routeName): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret-pass-1')]);
        $user->assignRole($role);

        $this->post('/login', ['email' => $user->email, 'password' => 'secret-pass-1'])
            ->assertRedirect(route($routeName));
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function login_without_role_goes_to_temp_dashboard(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret-pass-1')]);

        $this->post('/login', ['email' => $user->email, 'password' => 'secret-pass-1'])
            ->assertRedirect(route('temp.index'));
    }

    #[Test]
    public function login_rejects_wrong_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret-pass-1')]);

        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'nope'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    #[Test]
    public function inactive_user_cannot_log_in(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret-pass-1'), 'activo' => false]);
        $user->assignRole('Cliente');

        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'secret-pass-1'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    #[Test]
    public function password_reset_works_end_to_end(): void
    {
        Notification::fake();
        $user = User::factory()->create(['password' => Hash::make('old-password-1')]);

        $this->post('/forgot-password', ['email' => $user->email])->assertSessionHasNoErrors();

        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function ($n) use (&$token) {
            $token = $n->token;

            return true;
        });

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'New-Str0ng-Pass!x',
            'password_confirmation' => 'New-Str0ng-Pass!x',
        ])->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('New-Str0ng-Pass!x', $user->fresh()->password));
    }
}
