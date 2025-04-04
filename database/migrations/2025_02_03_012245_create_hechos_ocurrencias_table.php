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
        if (!Schema::hasTable('hechos_ocurrencias')) {
            Schema::create('hechos_ocurrencias', function (Blueprint $table) {
                $table->id();
                $table->string('cedula_numero'); // Clave foránea de afiliados
                $table->date('dia');
                $table->time('horas');
                $table->string('lugar');
                $table->unsignedBigInteger('motivo_muerte'); // Clave foránea de motivos de muerte
                $table->string('otros')->nullable();
                $table->string('deceso_se_origna')->nullable();
                $table->string('donde_fallese')->nullable();
                $table->string('funeraria')->nullable();
                $table->string('fallecido')->nullable();
                $table->string('cuerpo_fue')->nullable();
                $table->string('servicos_funerarios')->nullable();
                $table->timestamps();

                // Claves foráneas
                $table->foreign('cedula_numero')->references('cedula_numero')->on('afiliados')->onDelete('cascade');
                $table->foreign('motivo_muerte')->references('id')->on('motivos_muerte')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hechos_ocurrencias');
    }
};
