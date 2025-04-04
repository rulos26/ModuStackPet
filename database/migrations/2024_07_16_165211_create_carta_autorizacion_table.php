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
        if (!Schema::hasTable('carta_autorizaciones')) {
            Schema::create('carta_autorizaciones', function (Blueprint $table) {
                $table->id();
                $table->integer('cedula');
                $table->boolean('firmado');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carta_autorizaciones'); // Se corrigió el nombre de la tabla
    }
};
