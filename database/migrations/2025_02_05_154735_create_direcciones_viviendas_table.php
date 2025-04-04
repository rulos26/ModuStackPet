<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

class CreateDireccionesViviendasTable extends Migration
{
    public function up()
    {
        // Verificamos si la tabla ya existe antes de crearla
        if (!Schema::hasTable('direcciones_viviendas')) {
            Schema::create('direcciones_viviendas', function (Blueprint $table) {
                $table->id(); // Agrega una columna 'id' como clave primaria
                $table->string('cedula_numero'); // Clave foránea a la tabla afiliados (cedula_numero)
                $table->string('direccion_residencia'); // Dirección de residencia
                $table->unsignedBigInteger('tipo_de_vivienda');
                $table->unsignedBigInteger('tipo_de_propiedad');
                $table->date('vive_desde'); // Fecha desde cuando vive allí
                $table->timestamps(); // Registra las fechas de creación y actualización

                // Clave foránea con la tabla afiliados
                $table->foreign('cedula_numero')->references('cedula_numero')->on('afiliados')->onDelete('cascade');
                $table->foreign('tipo_de_vivienda')->references('id')->on('tipos_de_viviendas')->onDelete('cascade');
                $table->foreign('tipo_de_propiedad')->references('id')->on('tipos_de_propiedades')->onDelete('cascade');

            });
        }
    }

    public function down()
    {
        // Comprobamos si la tabla existe antes de eliminarla
        if (Schema::hasTable('direcciones_viviendas')) {
            Schema::dropIfExists('direcciones_viviendas');
        }
    }
}
