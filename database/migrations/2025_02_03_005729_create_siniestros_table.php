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
        if (!Schema::hasTable('siniestros')) {
            Schema::create('siniestros', function (Blueprint $table) {
                $table->id();
                $table->string('cedula_numero'); // Clave foránea de afiliados
                $table->date('fecha_siniestro');
                $table->string('lugar');
                $table->unsignedBigInteger('departamento');
                $table->unsignedBigInteger('municipio');
                $table->timestamps();

                $table->foreign('cedula_numero')->references('cedula_numero')->on('afiliados')->onDelete('cascade');
                $table->foreign('departamento')->references('id')->on('departamentos')->onDelete('cascade');
                $table->foreign('municipio')->references('id')->on('municipios')->onDelete('cascade');
                
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siniestros');
    }
};
