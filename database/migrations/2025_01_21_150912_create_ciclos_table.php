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
        Schema::create('ciclos', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_ciclo');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->bigInteger('estado')->unsigned(); // Cambiado a bigInteger y unsigned
            $table->foreign('estado')
                ->references('id')
                ->on('estados_ciclos'); // No usamos onDelete('cascade')
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciclos');
    }
};
