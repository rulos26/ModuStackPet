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
        Schema::create('imagen_reclamantes', function (Blueprint $table) {
            $table->id();
            $table->string('cedula_numero');
            $table->string('tipo_imagen'); // reclamante general, medio cuerpo, etc.
            $table->string('ruta_imagen');
            $table->timestamps();
            
            $table->foreign('cedula_numero')->references('cedula_numero')->on('afiliados')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagen_reclamantes');
    }
};
