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
        Schema::create('porcentajes_ahorros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('metodo_id')->constrained('metodos_ahorros')->onDelete('cascade');
            $table->decimal('porcentaje_1', 5, 2)->default(0); // Primer porcentaje
            $table->decimal('porcentaje_2', 5, 2)->default(0); // Segundo porcentaje
            $table->decimal('porcentaje_3', 5, 2)->default(0); // Tercer porcentaje
            $table->decimal('porcentaje_4', 5, 2)->default(0); // Cuarto porcentaje
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('porcentajes_ahorros');
    }
};
