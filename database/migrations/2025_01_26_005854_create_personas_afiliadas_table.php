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
        if (!Schema::hasTable('personas_afiliadas')) {
            Schema::create('personas_afiliadas', function (Blueprint $table) {
                $table->id();
                $table->string('nombres_apellidos');
                $table->string('cedula')->unique();
                $table->integer('edad');
                $table->date('fecha_nacimiento');
                $table->unsignedBigInteger('lugar_nacimiento_id');
                $table->date('fecha_siniestro');
                $table->unsignedBigInteger('lugar_siniestro_id');
                $table->unsignedBigInteger('estado_civil_siniestro_id');
                $table->date('estado_civil_desde')->nullable();
                $table->date('estado_civil_hasta')->nullable();
                $table->integer('hijos')->default(0);
                $table->string('edad_hijos')->nullable();
                $table->string('relacion_con')->nullable();
                $table->string('convivencia_con')->nullable();
                $table->string('tiempo_convivencia')->nullable();
                $table->string('direccion_residencia');
                $table->boolean('titular_trabaja')->default(false);
                $table->string('empresa')->nullable();
                $table->string('cargo')->nullable();
                $table->string('tiempo_laboral')->nullable();
                $table->decimal('salario', 10, 2)->nullable();
                $table->string('telefono');
                $table->unsignedBigInteger('cobertura_salud_id');
                $table->unsignedBigInteger('tipo_afiliacion_id');
                $table->boolean('registra_beneficiarios')->default(false);
                $table->text('observaciones')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas_afiliadas'); // Se corrigió el nombre de la tabla
    }
};
