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
        if (!Schema::hasTable('afiliados_coberturas_saluds')) {
            Schema::create('afiliados_coberturas_saluds', function (Blueprint $table) {
                $table->id(); // ID auto incremental para la tabla
                $table->string('cedula_numero'); // Clave foránea a la tabla afiliados
                $table->string('cobertura_salud'); // EPS, ARL, Medicina prepagada, etc.
                $table->string('tipo_afiliacion'); // Cotizante, beneficiario, etc.
                $table->string('regimen'); // Régimen contributivo, subsidiado, etc.
                $table->date('desde'); // Fecha de inicio de cobertura
                $table->boolean('registra_beneficiarios')->default(false); // Si registra beneficiarios (Sí/No)
                $table->string('quien_reclama_prestaciones_sociales')->nullable(); // Persona que reclama prestaciones sociales
                
                $table->timestamps(); // Fechas de creación y actualización

                // Claves foráneas
                $table->foreign('cedula_numero')->references('cedula_numero')->on('afiliados')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afiliados_coberturas_saluds');
    }
};
