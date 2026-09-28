<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EncryptCredentialsMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_migration_is_idempotent_and_does_not_double_encrypt(): void
    {
        // Simula una fila que quedó en texto plano antes de correr la migración
        // (por ejemplo, insertada por un seeder o import externo).
        $id = DB::table('database_configs')->insertGetId([
            'connection' => 'mysql',
            'host' => '127.0.0.1',
            'port' => 3306,
            'database' => 'legacy_db',
            'username' => 'user',
            'password' => 'plain-text-legacy-password',
            'is_active' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $migration = require database_path('migrations/2025_11_06_000000_encrypt_credentials_columns.php');

        // Primera corrida: debe cifrar el valor en texto plano.
        $migration->up();
        $afterFirstRun = DB::table('database_configs')->where('id', $id)->value('password');
        $this->assertNotSame('plain-text-legacy-password', $afterFirstRun);
        $this->assertSame('plain-text-legacy-password', Crypt::decryptString($afterFirstRun));

        // Segunda corrida: no debe volver a cifrar un valor ya cifrado.
        $migration->up();
        $afterSecondRun = DB::table('database_configs')->where('id', $id)->value('password');
        $this->assertSame($afterFirstRun, $afterSecondRun);
        $this->assertSame('plain-text-legacy-password', Crypt::decryptString($afterSecondRun));
    }
}
