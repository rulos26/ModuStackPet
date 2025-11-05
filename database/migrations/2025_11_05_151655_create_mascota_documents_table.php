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
        $hasMascotas = Schema::hasTable('mascotas');
        $hasDocumentRequirements = Schema::hasTable('document_requirements');
        
        Schema::create('mascota_documents', function (Blueprint $table) use ($hasMascotas, $hasDocumentRequirements) {
            $table->id();
            
            if ($hasMascotas) {
                $table->foreignId('mascota_id')->constrained('mascotas')->onDelete('cascade');
            } else {
                $table->unsignedBigInteger('mascota_id');
            }
            
            if ($hasDocumentRequirements) {
                $table->foreignId('document_requirement_id')->constrained('document_requirements')->onDelete('cascade');
            } else {
                $table->unsignedBigInteger('document_requirement_id');
            }
            
            $table->string('nombre_archivo', 255); // Nombre original del archivo
            $table->string('ruta_archivo', 500); // Ruta donde se almacena
            $table->string('tipo_mime', 100)->nullable(); // Tipo MIME del archivo
            $table->integer('tamaño_bytes')->nullable(); // Tamaño en bytes
            $table->string('hash_archivo', 64)->nullable(); // SHA-256 hash para verificación
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado', 'pendiente_correccion'])->default('pendiente');
            $table->text('motivo_rechazo')->nullable(); // Motivo si fue rechazado
            $table->date('fecha_emision')->nullable(); // Fecha de emisión del documento
            $table->date('fecha_vencimiento')->nullable(); // Fecha de vencimiento (si aplica)
            $table->boolean('validacion_automatica')->default(false); // Si pasó validación automática
            $table->json('detalles_validacion')->nullable(); // Detalles de la validación (fechas, campos detectados, etc.)
            $table->foreignId('usuario_subio_id')->nullable()->constrained('users')->nullOnDelete(); // Usuario que subió el documento
            $table->foreignId('usuario_aprobo_id')->nullable()->constrained('users')->nullOnDelete(); // Usuario que aprobó (si aplica)
            $table->timestamp('fecha_aprobacion')->nullable();
            $table->text('notas')->nullable(); // Notas adicionales
            $table->timestamps();
            $table->softDeletes(); // Para auditoría
            
            // Índices
            $table->index('mascota_id');
            $table->index('document_requirement_id');
            $table->index('estado');
            $table->index('fecha_vencimiento');
            $table->index(['mascota_id', 'document_requirement_id']); // Para evitar duplicados
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascota_documents');
    }
};
