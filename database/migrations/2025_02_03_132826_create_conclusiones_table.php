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
        if (!Schema::hasTable('conclusiones')) {
            Schema::create('conclusiones', function (Blueprint $table) {
                $table->id();
                $table->string('cedula_numero'); // Clave foránea de afiliados
                $table->enum('documentos', ['SON', 'NO SON']);
                $table->enum('nexos', ['SON', 'NO SON']);
                $table->unsignedBigInteger('muerte_origen'); // Clave foránea de muertes_origen
                //$table->unsignedBigInteger('reclamante'); // Clave foránea de reclamantes
                //$table->string('nombre_reclamante');
                //$table->enum('afiliado_deja_descendiente', ['SI', 'NO']);
                //$table->enum('descendientes_relacion', ['SI', 'NO']);
                //$table->enum('descendientes_afiliado', ['SI', 'NO']);
                //$table->unsignedBigInteger('datos_hijo')->nullable(); // Clave foránea de datos_hijos
                //$table->enum('presenta_condicion_discapacidad', ['SI', 'NO']);
                $table->string('observaciones');
                $table->timestamps();

                // Claves foráneas
                $table->foreign('cedula_numero')->references('cedula_numero')->on('afiliados')->onDelete('cascade');
                $table->foreign('muerte_origen')->references('id')->on('muertes_origen')->onDelete('cascade');
                //$table->foreign('reclamante')->references('id')->on('reclamantes')->onDelete('cascade');
                //$table->foreign('datos_hijo')->references('id')->on('datos_basicos_hijos')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conclusiones');
    }
};
