<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('afiliados')) { // Verifica si la tabla ya existe
            Schema::create('afiliados', function (Blueprint $table) {
                // Usamos cedula_numero en lugar de cedula
                $table->string('cedula_numero')->primary(); // La cédula como clave primaria
                $table->integer('edad');
                $table->date('fecha_nacimiento');
                $table->unsignedBigInteger('departamento');
                $table->unsignedBigInteger('municipio');
                $table->date('fecha_expedicion');
                $table->timestamps(); // Registra las fechas de creación y actualización

                // Claves foráneas
                $table->foreign('cedula_numero')->references('cedula_numero')->on('datos_basicos')->onDelete('cascade');
                $table->foreign('departamento')->references('id')->on('departamentos')->onDelete('cascade');
                $table->foreign('municipio')->references('id')->on('municipios')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('afiliados'); // Elimina la tabla si existe
    }
};
