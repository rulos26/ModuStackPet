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
        if (!Schema::hasTable('mascotas')) {
            $hasUsers = Schema::hasTable('users');
            $hasRazas = Schema::hasTable('razas');
            
            Schema::create('mascotas', function (Blueprint $table) use ($hasUsers, $hasRazas) {
                $table->id();
                if ($hasUsers) {
                    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // propietario
                } else {
                    $table->unsignedBigInteger('user_id');
                }
                $table->string('avatar')->nullable();
                $table->string('nombre');
                $table->integer('edad')->nullable()->unsigned(); // Asegura que la edad sea positiva
                $table->date('fecha_nacimiento')->nullable();
                if ($hasRazas) {
                    $table->foreignId('raza_id')->nullable()->constrained('razas')->nullOnDelete();
                } else {
                    $table->unsignedBigInteger('raza_id')->nullable();
                }
                $table->enum('genero', ['Macho', 'Hembra'])->nullable();
                $table->boolean('vacunas_completas')->default(false);
                $table->date('ultima_vacunacion')->nullable();
                $table->text('comportamiento')->nullable();
                $table->string('direccion')->nullable();
                $table->string('interior_apto')->nullable();
                $table->unsignedBigInteger('barrio_id')->nullable();
                $table->text('recomendaciones')->nullable();
                $table->boolean('esterilizado')->default(false);
                $table->text('enfermedades')->nullable();
                $table->date('ultimo_examen_medico')->nullable();
                $table->timestamps();

                // Agregar índices para mejorar el rendimiento de las consultas
                $table->index('user_id');
                $table->index('raza_id');
                $table->index('barrio_id');
                $table->index('fecha_nacimiento');
                $table->index('ultima_vacunacion');
                $table->index('ultimo_examen_medico');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};
