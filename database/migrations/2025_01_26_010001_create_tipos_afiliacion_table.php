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
        if (!Schema::hasTable('tipos_afiliaciones')) {
            Schema::create('tipos_afiliaciones', function (Blueprint $table) {
                $table->id();
                $table->string('tipo_afiliacion')->unique();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_afiliaciones'); // Se corrigió el nombre de la tabla
    }
};
