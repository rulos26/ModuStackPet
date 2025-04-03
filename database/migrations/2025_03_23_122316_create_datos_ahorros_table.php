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
        Schema::create('datos_ahorros', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con usuarios
            $table->decimal('sueldo', 10, 2); // Sueldo con 2 decimales
            $table->foreignId('metodo_ahorro_id')->constrained('metodos_ahorros')->onDelete('cascade'); // Relación con la tabla metodos_ahorros
            $table->date('fecha_inicio'); // Fecha de inicio del ahorro
            $table->date('fecha_fin'); // Fecha final del ahorro
            $table->foreignId('mes_id')->constrained('meses')->onDelete('cascade');

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_ahorros');
    }
};
