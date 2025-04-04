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
        if (!Schema::hasTable('datos_basicos_hijos')) { // Validar si la tabla ya existe
            Schema::create('datos_basicos_hijos', function (Blueprint $table) {
                $table->id();
                $table->string('cedula_numero'); // Clave foránea a la tabla afiliados
                $table->integer('numero_hijos')->nullable();;
                $table->string('nombre');
                $table->unsignedBigInteger('tipo_documento'); // Clave foránea a tipo_documentos
                $table->string('documento')->unique();
                $table->integer('edad');
                $table->timestamps();

                // Definir claves foráneas
                $table->foreign('cedula_numero')->references('cedula_numero')->on('afiliados')->onDelete('cascade');
                $table->foreign('tipo_documento')->references('id')->on('tipo_documentos')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('datos_basicos_hijos')) {
            Schema::dropIfExists('datos_basicos_hijos');
        }
    }
};
