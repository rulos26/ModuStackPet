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
        if (!Schema::hasTable('sectores')) {
            Schema::create('sectores', function (Blueprint $table) {
                $table->id();
                $table->string('nombre'); // Ej. Tecnología, Salud, Construcción
                $table->timestamps();
                $table->softDeletes(); // Agrega la columna deleted_at
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sectores');
    }
};
