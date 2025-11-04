<?php

namespace Database\Seeders;

use App\Models\OAuthProvider;
use Illuminate\Database\Seeder;

class OAuthProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            [
                'provider' => 'google',
                'name' => 'Google',
                'client_id' => null,
                'client_secret' => null,
                'redirect_uri' => env('APP_URL') . '/auth/google/callback',
                'is_active' => false,
                'description' => 'Autenticación con Google OAuth 2.0',
            ],
            [
                'provider' => 'facebook',
                'name' => 'Facebook',
                'client_id' => null,
                'client_secret' => null,
                'redirect_uri' => env('APP_URL') . '/auth/facebook/callback',
                'is_active' => false,
                'description' => 'Autenticación con Facebook OAuth',
            ],
            [
                'provider' => 'github',
                'name' => 'GitHub',
                'client_id' => null,
                'client_secret' => null,
                'redirect_uri' => env('APP_URL') . '/auth/github/callback',
                'is_active' => false,
                'description' => 'Autenticación con GitHub OAuth',
            ],
            [
                'provider' => 'twitter',
                'name' => 'Twitter/X',
                'client_id' => null,
                'client_secret' => null,
                'redirect_uri' => env('APP_URL') . '/auth/twitter/callback',
                'is_active' => false,
                'description' => 'Autenticación con Twitter/X OAuth',
            ],
            [
                'provider' => 'linkedin',
                'name' => 'LinkedIn',
                'client_id' => null,
                'client_secret' => null,
                'redirect_uri' => env('APP_URL') . '/auth/linkedin/callback',
                'is_active' => false,
                'description' => 'Autenticación con LinkedIn OAuth',
            ],
        ];

        foreach ($providers as $providerData) {
            OAuthProvider::firstOrCreate(
                ['provider' => $providerData['provider']],
                $providerData
            );
        }
    }
}
