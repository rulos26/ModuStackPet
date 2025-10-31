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
            $hasMascotas = Schema::hasTable('mascotas');
            Schema::create('vacunas_certificaciones', function (Blueprint $table) use ($hasMascotas) {
                $table->id();
                $table->string('nombre');
                $table->string('tipo');
                $table->date('fecha_vencimiento');
                $table->string('archivo')->nullable();
                if ($hasMascotas) {
                    $table->foreignId('mascota_id')->constrained('mascotas')->onDelete('cascade');
                } else {
                    $table->unsignedBigInteger('mascota_id');
                }
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
