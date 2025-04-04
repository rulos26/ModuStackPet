<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiposDeViviendasTable extends Migration
{
    public function up()
    {
        Schema::create('tipos_de_viviendas', function (Blueprint $table) {
            $table->id(); // Crea el campo 'id' como clave primaria autoincrementable
            $table->string('nombre'); // Crea el campo 'nombre' de tipo string
            $table->timestamps(); // Agrega los campos 'created_at' y 'updated_at'
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipos_de_viviendas');
    }
}
