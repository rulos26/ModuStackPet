<?php

namespace Tests\Feature;

use App\Models\OAuthProvider;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SocialAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'Cliente']);
    }

    #[Test]
    public function active_configured_provider_redirects_to_its_authorization_page(): void
    {
        $this->createProvider();
        $driver = Mockery::mock(Provider::class);

        Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturn($driver);
        $driver->shouldReceive('redirect')
            ->once()
            ->andReturn(redirect()->away('https://accounts.example.test/oauth'));

        $this->get(route('social.redirect', 'google'))
            ->assertRedirect('https://accounts.example.test/oauth');
    }

    #[Test]
    public function callback_creates_new_user_cliente_profile_and_social_account(): void
    {
        $this->createProvider();
        $this->mockSocialUser($this->socialUser(
            id: 'google-new-1',
            email: 'nueva@example.test',
            name: 'Nueva OAuth',
        ));

        $response = $this->get(route('social.callback', 'google'));

        $user = User::where('email', 'nueva@example.test')->firstOrFail();

        $response->assertRedirect(route('cliente.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->hasRole('Cliente'));
        $this->assertNotNull($user->email_verified_at);
        $this->assertDatabaseHas('clientes', [
            'user_id' => $user->id,
            'nombre' => 'Nueva OAuth',
        ]);
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => 'google-new-1',
        ]);
    }

    #[Test]
    public function callback_links_existing_user_by_email_instead_of_duplicating_it(): void
    {
        $this->createProvider();
        $user = User::factory()->create(['email' => 'existente@example.test']);
        $user->assignRole('Cliente');
        $this->mockSocialUser($this->socialUser(
            id: 'google-linked-1',
            email: 'existente@example.test',
            name: 'Nombre del proveedor',
        ));

        $response = $this->get(route('social.callback', 'google'));

        $response->assertRedirect(route('cliente.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => 'google-linked-1',
        ]);
    }

    #[Test]
    public function callback_logs_in_existing_social_account_without_creating_duplicates(): void
    {
        $this->createProvider();
        $user = User::factory()->create(['email' => 'social@example.test']);
        $user->assignRole('Cliente');
        SocialAccount::create([
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => 'google-existing-1',
        ]);
        $this->mockSocialUser($this->socialUser(
            id: 'google-existing-1',
            email: 'otro-correo@example.test',
            name: 'Cuenta existente',
        ));

        $response = $this->get(route('social.callback', 'google'));

        $response->assertRedirect(route('cliente.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('social_accounts', 1);
    }

    #[Test]
    public function inactive_provider_is_rejected_before_socialite_is_called(): void
    {
        $this->createProvider(['is_active' => false]);
        Socialite::shouldReceive('driver')->never();

        $this->get(route('social.redirect', 'google'))
            ->assertForbidden();
    }

    #[Test]
    public function unconfigured_provider_is_rejected_before_socialite_is_called(): void
    {
        $this->createProvider(['client_secret' => null]);
        Socialite::shouldReceive('driver')->never();

        $this->get(route('social.redirect', 'google'))
            ->assertForbidden();
    }

    #[Test]
    public function unknown_provider_returns_not_found_instead_of_server_error(): void
    {
        Socialite::shouldReceive('driver')->never();

        $this->get(route('social.redirect', 'desconocido'))
            ->assertNotFound();
    }

    #[Test]
    public function callback_handles_socialite_exception_and_returns_to_login(): void
    {
        $this->createProvider();
        Log::spy();
        $driver = Mockery::mock(Provider::class);

        Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturn($driver);
        $driver->shouldReceive('user')
            ->once()
            ->andThrow(new RuntimeException('Proveedor no disponible'));

        $this->get(route('social.callback', 'google'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('error');

        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('social_accounts', 0);
        Log::shouldHaveReceived('error')->once();
    }

    private function createProvider(array $attributes = []): OAuthProvider
    {
        return OAuthProvider::create(array_merge([
            'provider' => 'google',
            'name' => 'Google',
            'client_id' => 'client-id-test',
            'client_secret' => 'client-secret-test',
            'redirect_uri' => 'https://app.example.test/auth/google/callback',
            'is_active' => true,
        ], $attributes));
    }

    private function mockSocialUser(SocialiteUser $socialUser): void
    {
        $driver = Mockery::mock(Provider::class);

        Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturn($driver);
        $driver->shouldReceive('user')
            ->once()
            ->andReturn($socialUser);
    }

    private function socialUser(string $id, string $email, string $name): SocialiteUser
    {
        return SocialiteUser::fake([
            'id' => $id,
            'email' => $email,
            'name' => $name,
            'nickname' => null,
            'avatar' => 'https://images.example.test/avatar.png',
        ]);
    }
}
