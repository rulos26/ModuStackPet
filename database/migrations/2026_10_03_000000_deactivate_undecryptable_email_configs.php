<?php

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * SEG-031b: desactiva las filas de email_configs cuyo password no se puede
 * descifrar con la APP_KEY actual ("The payload is invalid"). Causaban un
 * warning en cada petición y el sistema ya caía a .env. No borra datos.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('email_configs')) {
            return;
        }

        DB::table('email_configs')
            ->where('is_active', true)
            ->whereNotNull('password')
            ->where('password', '!=', '')
            ->select('id', 'password')
            ->orderBy('id')
            ->each(function ($row) {
                try {
                    Crypt::decryptString($row->password);
                } catch (DecryptException) {
                    DB::table('email_configs')->where('id', $row->id)->update(['is_active' => false]);
                }
            });
    }

    public function down(): void
    {
        // Irreversible a propósito: no se puede saber qué filas estaban activas.
    }
};
