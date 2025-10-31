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
        if (!Schema::hasTable('paseadores')) {
            $hasUsers = Schema::hasTable('users');
            $hasTipoDocumentos = Schema::hasTable('tipo_documentos');
            $hasCiudades = Schema::hasTable('ciudades');
            $hasBarrios = Schema::hasTable('barrios');
            
            Schema::create('paseadores', function (Blueprint $table) use ($hasUsers, $hasTipoDocumentos, $hasCiudades, $hasBarrios) {
                $table->id();
                if ($hasUsers) {
                    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                } else {
                    $table->unsignedBigInteger('user_id');
                }
                if ($hasTipoDocumentos) {
                    $table->foreignId('tipo_documento_id')->nullable()->constrained('tipo_documentos')->nullOnDelete();
                } else {
                    $table->unsignedBigInteger('tipo_documento_id')->nullable();
                }
                $table->string('cedula')->unique();
                $table->string('telefono')->nullable();
                $table->string('whatsapp')->nullable();
                $table->date('fecha_nacimiento')->nullable();
                $table->string('direccion')->nullable();
                // ciudad_id debe referenciar id_municipio en ciudades, no id
                $table->unsignedBigInteger('ciudad_id')->nullable();
                if ($hasBarrios) {
                    $table->foreignId('barrio_id')->nullable()->constrained('barrios')->nullOnDelete();
                } else {
                    $table->unsignedBigInteger('barrio_id')->nullable();
                }
                $table->integer('experiencia_anos')->default(0);
                $table->decimal('calificacion_promedio', 3, 2)->default(0);
                $table->boolean('disponibilidad')->default(true);
                $table->decimal('tarifa_hora', 10, 2)->nullable();
                $table->json('documentos_verificados')->nullable();
                $table->string('avatar')->nullable();
                $table->timestamps();
                
                // Índices para mejorar rendimiento
                $table->index('user_id');
                $table->index('ciudad_id');
                $table->index('barrio_id');
            });
            
            // Agregar foreign key de ciudad_id después de crear la tabla (referencia id_municipio)
            if ($hasCiudades) {
                try {
                    Schema::table('paseadores', function (Blueprint $table) {
                        $table->foreign('ciudad_id')
                              ->references('id_municipio')
                              ->on('ciudades')
                              ->nullOnDelete();
                    });
                } catch (\Exception $e) {
                    // Foreign key ya existe
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paseadores');
    }
};
