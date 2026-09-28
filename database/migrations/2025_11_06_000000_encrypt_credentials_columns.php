<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla => columna secreta a cifrar.
     */
    private array $targets = [
        'database_configs' => 'password',
        'email_configs' => 'password',
        'backup_configs' => 'password',
        'oauth_providers' => 'client_secret',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->targets as $table => $column) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($table, $column) {
                $blueprint->text($column)->nullable()->change();
            });

            DB::table($table)->select('id', $column)->orderBy('id')->each(function ($row) use ($table, $column) {
                $value = $row->{$column};

                if ($value === null || $value === '' || $this->isAlreadyEncrypted($value)) {
                    return;
                }

                DB::table($table)->where('id', $row->id)->update([
                    $column => Crypt::encryptString($value),
                ]);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->targets as $table => $column) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
                continue;
            }

            DB::table($table)->select('id', $column)->orderBy('id')->each(function ($row) use ($table, $column) {
                $value = $row->{$column};

                if ($value === null || $value === '' || !$this->isAlreadyEncrypted($value)) {
                    return;
                }

                DB::table($table)->where('id', $row->id)->update([
                    $column => Crypt::decryptString($value),
                ]);
            });
        }
    }

    /**
     * Determina si un valor ya está cifrado con Crypt, para que la migración
     * sea idempotente (no volver a cifrar valores que ya lo están).
     */
    private function isAlreadyEncrypted(string $value): bool
    {
        try {
            Crypt::decryptString($value);

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
};
