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
        Schema::create('metodos_ahorro', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50); // Nombre del método
            $table->text('descripcion'); // Explicación del método
            $table->json('porcentajes')->nullable(); // Estructura de distribución en JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metodos_ahorro');
    }
};
