<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('verificaciones')) {
            Schema::create('verificaciones', function (Blueprint $table) {
                $table->id();
                
                // Relación con afiliados
                $table->string('cedula_numero');
                
                // Documentos a verificar (Texto largo para observaciones o detalles)
                $table->text('cedula_afiliado')->nullable();
                $table->text('registro_civil_nacimiento_afiliado')->nullable();
                $table->text('registro_defuncion_afiliado')->nullable();
                $table->text('cedula_reclamante')->nullable();
                $table->text('registro_civil_nacimiento_descendiente')->nullable();

                $table->timestamps();

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
        Schema::dropIfExists('verificaciones');
    }
};
