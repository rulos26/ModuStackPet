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
        Schema::create('ciudades', function (Blueprint $table) {
            $table->id('id_municipio');
            $table->string('municipio', 100);
            $table->unsignedBigInteger('departamento_id');
            $table->tinyInteger('estado')->default(1);
            $table->timestamps();
            $table->softDeletes();

            // Clave foránea para departamento
            $table->foreign('departamento_id')
                  ->references('id_departamento')
                  ->on('departamentos')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciudades');
    }
};
