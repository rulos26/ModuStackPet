<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('configuraciones')) {
            Schema::create('configuraciones', function (Blueprint $table) {
                $table->id();
                $table->string('clave')->unique(); // Ejemplo: 'session_timeout'
                $table->string('valor'); // Valor de la configuración
                $table->string('descripcion')->nullable(); // Descripción para el usuario
                $table->string('tipo')->default('text'); // text, number, boolean, etc.
                $table->string('categoria')->default('sistema'); // sistema, seguridad, etc.
                $table->boolean('activo')->default(true);
                $table->timestamps();

                $table->index('clave');
                $table->index('categoria');
            });

            // Insertar configuración inicial del timeout de sesión solo si no existe
            if (!DB::table('configuraciones')->where('clave', 'session_timeout')->exists()) {
                DB::table('configuraciones')->insert([
                    'clave' => 'session_timeout',
                    'valor' => '1800',
                    'descripcion' => 'Tiempo de inactividad antes de cerrar sesión (en segundos). 1800 = 30 minutos',
                    'tipo' => 'number',
                    'categoria' => 'seguridad',
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};
