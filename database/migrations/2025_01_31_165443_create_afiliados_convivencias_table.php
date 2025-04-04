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
        // Verificamos si la tabla ya existe antes de crearla
        if (!Schema::hasTable('afiliados_convivencias')) {
            Schema::create('afiliados_convivencias', function (Blueprint $table) {
                $table->id(); // ID auto incremental para la tabla
                $table->string('cedula_numero'); // Clave foránea a la tabla afiliados (cedula_numero)
                $table->unsignedBigInteger('estado_civil_al_siniestro'); // Estado civil al momento del siniestro
                $table->date('desde_estado_civil'); // Fecha desde cuando tiene ese estado civil
                $table->date('hasta_estado_civil'); // Fecha hasta cuando tiene ese estado civil
                $table->string('relacion_con'); // Relación con la persona (por ejemplo, pareja, compañero, etc.)
                $table->string('quien_convivía'); // Quien convivía con la persona (nombre o relación)
                $table->string('tiempo_convivencia'); // Tiempo de convivencia (puede ser en años o meses)
                $table->date('desde_convivencia'); // Fecha desde cuando convivía con la persona
                $table->date('hasta_convivencia'); // Fecha hasta cuando convivió con la persona
                $table->timestamps(); // Registra las fechas de creación y actualización

                // Claves foráneas
                $table->foreign('cedula_numero')->references('cedula_numero')->on('afiliados')->onDelete('cascade');
                $table->foreign('estado_civil_al_siniestro')->references('id')->on('estados_civiles')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Comprobamos si la tabla existe antes de eliminarla
        if (Schema::hasTable('afiliados_convivencias')) {
            Schema::dropIfExists('afiliados_convivencias');
        }
    }
};
