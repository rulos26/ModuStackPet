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
        if (!Schema::hasTable('vacunas_certificaciones')) {
            Schema::create('vacunas_certificaciones', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->string('tipo');
                $table->date('fecha_vencimiento');
                $table->string('archivo')->nullable();
                $table->foreignId('mascota_id')->constrained('mascotas')->onDelete('cascade');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacunas_certificaciones');
    }
};
