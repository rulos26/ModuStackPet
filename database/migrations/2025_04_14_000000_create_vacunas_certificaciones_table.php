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
        // Verificar si la tabla ya existe
        if (!Schema::hasTable('vacunas_certificaciones')) {
            // Verificar si existe la tabla mascotas
            if (Schema::hasTable('mascotas')) {
                Schema::create('vacunas_certificaciones', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('id_mascota')->constrained('mascotas')->onDelete('cascade');
                    $table->date('fecha_ultima_vacuna');
                    $table->text('operaciones')->nullable();
                    $table->string('certificado_veterinario')->nullable();
                    $table->string('cedula_propietario')->nullable();
                    $table->timestamps();
                });
            } else {
                // Si no existe la tabla mascotas, lanzar una excepción
                throw new \Exception('La tabla mascotas no existe. Por favor, ejecute primero la migración de mascotas.');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Verificar si la tabla existe antes de intentar eliminarla
        if (Schema::hasTable('vacunas_certificaciones')) {
            Schema::dropIfExists('vacunas_certificaciones');
        }
    }
};
