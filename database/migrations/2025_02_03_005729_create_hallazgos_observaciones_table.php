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
        if (!Schema::hasTable('hallazgos_observaciones')) {
            Schema::create('hallazgos_observaciones', function (Blueprint $table) {
                $table->id();
                $table->string('cedula_numero'); // Clave foránea de afiliados
                $table->text('hallazgos');
                $table->text('observaciones')->nullable();
                $table->timestamps();

                $table->foreign('cedula_numero')->references('cedula_numero')->on('afiliados')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hallazgos_observaciones');
    }
};
