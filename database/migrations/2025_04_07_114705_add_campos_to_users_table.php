<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'tipo_documento')) {
                    $table->string('tipo_documento')->nullable();
                }
                if (!Schema::hasColumn('users', 'cedula')) {
                    $table->string('cedula')->nullable()->unique();
                }
                if (!Schema::hasColumn('users', 'avatar')) {
                    $table->string('avatar')->nullable();
                }
                if (!Schema::hasColumn('users', 'activo')) {
                    $table->boolean('activo')->default(true);
                }
                if (!Schema::hasColumn('users', 'telefono')) {
                    $table->string('telefono')->nullable();
                }
                if (!Schema::hasColumn('users', 'whatsapp')) {
                    $table->string('whatsapp')->nullable();
                }
                if (!Schema::hasColumn('users', 'fecha_nacimiento')) {
                    $table->date('fecha_nacimiento')->nullable(); // Paseador
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
