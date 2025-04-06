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
        Schema::create('mensaje_de_bienvenida', function (Blueprint $table) {
            $table->id(); // Campo ID autoincremental
            $table->string('titulo'); // Campo para el título
            $table->text('descripcion'); // Campo para la descripción
            $table->string('logo')->nullable(); // Campo para la ruta del logo (puede ser nulo)
            $table->string('rol'); // Campo para el rol asociado
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensaje_de_bienvenida');
    }
};
