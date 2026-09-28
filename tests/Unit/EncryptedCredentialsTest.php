<?php

namespace Tests\Unit;

use App\Models\BackupConfig;
use App\Models\DatabaseConfig;
use App\Models\EmailConfig;
use App\Models\OAuthProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EncryptedCredentialsTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_config_password_is_encrypted_at_rest(): void
    {
        $plain = 'super-secret-db-password';

        $config = DatabaseConfig::create([
            'connection' => 'mysql',
            'host' => '127.0.0.1',
            'port' => 3306,
            'database' => 'test_db',
            'username' => 'user',
            'password' => $plain,
        ]);

        $raw = DB::table('database_configs')->where('id', $config->id)->value('password');

        $this->assertNotSame($plain, $raw);
        $this->assertSame($plain, $config->fresh()->password);
    }

    public function test_email_config_password_is_encrypted_at_rest(): void
    {
        $plain = 'super-secret-mail-password';

        $config = EmailConfig::create([
            'mailer' => 'smtp',
            'host' => 'smtp.example.com',
            'port' => 587,
            'username' => 'user@example.com',
            'password' => $plain,
            'encryption' => 'tls',
            'from_address' => 'user@example.com',
        ]);

        $raw = DB::table('email_configs')->where('id', $config->id)->value('password');

        $this->assertNotSame($plain, $raw);
        $this->assertSame($plain, $config->fresh()->password);
    }

    public function test_backup_config_password_is_encrypted_at_rest(): void
    {
        $plain = 'super-secret-backup-password';

        $config = BackupConfig::create([
            'name' => 'Backup destino',
            'connection' => 'mysql',
            'host' => '127.0.0.1',
            'port' => 3306,
            'database' => 'backup_db',
            'username' => 'user',
            'password' => $plain,
        ]);

        $raw = DB::table('backup_configs')->where('id', $config->id)->value('password');

        $this->assertNotSame($plain, $raw);
        $this->assertSame($plain, $config->fresh()->password);
    }

    public function test_oauth_provider_client_secret_is_encrypted_at_rest(): void
    {
        $plain = 'super-secret-oauth-client-secret';

        $provider = OAuthProvider::create([
            'provider' => 'google',
            'name' => 'Google',
            'client_id' => 'client-id-123',
            'client_secret' => $plain,
            'redirect_uri' => 'https://example.com/callback',
        ]);

        $raw = DB::table('oauth_providers')->where('id', $provider->id)->value('client_secret');

        $this->assertNotSame($plain, $raw);
        $this->assertSame($plain, $provider->fresh()->client_secret);
    }
}
