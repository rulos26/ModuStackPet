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
        Schema::create('document_requirement_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_requirement_id')->constrained('document_requirements')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('accion', 50); // 'activado', 'desactivado', 'modificado'
            $table->json('valores_anteriores')->nullable(); // Valores antes del cambio
            $table->json('valores_nuevos')->nullable(); // Valores después del cambio
            $table->text('motivo')->nullable(); // Motivo del cambio
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requirement_logs');
    }
};
