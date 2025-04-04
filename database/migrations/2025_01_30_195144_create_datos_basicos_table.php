<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('datos_basicos', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('cedula_numero', 20)->unique();
            $table->string('nombre_afiliado', 255);
            $table->string('caso', 50)->unique();
            $table->date('fecha');
            $table->unsignedBigInteger('estado_civil'); // Relacionado con estados_civiles
            $table->unsignedBigInteger('amparo'); // Relacionado con amparos
            $table->unsignedBigInteger('tipo_de_convivencia'); // Relacionado con tipo_de_convivencia
            $table->string('otro', 255)->nullable();
            $table->timestamps();

            // Clave foránea (si decides normalizar estado civil)
            $table->foreign('estado_civil')->references('id')->on('estados_civiles')->onDelete('cascade');
            $table->foreign('amparo')->references('id')->on('amparos')->onDelete('cascade');
            $table->foreign('tipo_de_convivencia')->references('id')->on('tipo_de_convivencias')->onDelete('cascade');


        });
    }

    public function down()
    {
        Schema::dropIfExists('datos_basicos');
    }
};
