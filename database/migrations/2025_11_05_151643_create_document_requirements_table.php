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
        Schema::create('document_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique(); // VAC, DESP, SALUD, COMPORT, etc.
            $table->string('nombre', 255); // Nombre descriptivo
            $table->text('descripcion')->nullable(); // Descripción detallada
            $table->boolean('obligatorio')->default(true); // Si es obligatorio o no
            $table->boolean('activo')->default(true); // Si está activo en el sistema
            $table->integer('orden')->default(0); // Orden de visualización
            $table->string('tipo_validacion')->nullable(); // 'fecha_vencimiento', 'firma_digital', 'sello_veterinario', etc.
            $table->integer('dias_validez')->nullable(); // Días de validez (ej: 365 para vacunas, 90 para desparasitación)
            $table->json('formatos_permitidos')->nullable(); // ['pdf', 'jpg', 'png']
            $table->integer('tamaño_maximo_kb')->default(2048); // Tamaño máximo en KB
            $table->boolean('aplica_razas_peligrosas')->default(false); // Si aplica solo para razas peligrosas
            $table->timestamps();
            $table->softDeletes(); // Para auditoría
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requirements');
    }
};
