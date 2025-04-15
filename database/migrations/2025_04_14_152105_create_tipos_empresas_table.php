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
        Schema::create('tipos_empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // S.A., S.A.S., LTDA, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Primero eliminamos la tabla empresas que depende de tipos_empresas
        Schema::dropIfExists('empresas');
        // Luego podemos eliminar tipos_empresas
        Schema::dropIfExists('tipos_empresas');
    }
};
