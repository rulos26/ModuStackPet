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
        Schema::create('filtros_pdfs', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_modulo');
            $table->boolean('visible')->default(1); // Establecer default(0)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filtros_pdfs');
    }
};
