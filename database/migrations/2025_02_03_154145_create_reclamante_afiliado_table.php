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
        if (!Schema::hasTable('reclamantes_afiliados')) {
            Schema::create('reclamantes_afiliados', function (Blueprint $table) {
                $table->id();
                $table->string('cedula_numero'); // Relación con afiliados
                $table->unsignedBigInteger('reclamante'); // Relación con reclamantes
                $table->string('nombre');

                // Relación con tipo_documento
                $table->unsignedBigInteger('tipo_documento');
                $table->string('cedula_reclamante')->unique();

                $table->date('fecha_nacimiento');

                // Relaciones con ciudades y departamentos
                $table->unsignedBigInteger('ciudad_nacimiento');
                $table->unsignedBigInteger('departamento_nacimiento');

                $table->integer('edad');
                $table->date('fecha_expedicion');

                $table->unsignedBigInteger('ciudad_expedicion');
                $table->unsignedBigInteger('departamento_expedicion');

                // Estado civil
                $table->unsignedBigInteger('estado_civil');

                $table->date('desde_convivencia')->nullable();
                $table->date('hasta_convivencia')->nullable();
                $table->boolean('compartieron_techo_mesa_lecho')->default(false);
                $table->boolean('afiliado_relacion_quedaron_hijos')->default(false);

                // Relación con datos_basicos_hijo
                $table->unsignedBigInteger('datos_basicos_hijo')->nullable();

                $table->string('direccion_siniestro');
                $table->string('direccion_actual');
                $table->string('barrio');
                $table->unsignedBigInteger('ciudad');

                $table->string('vivienda');
                $table->decimal('canon_arrendamiento', 10, 2)->nullable();
                $table->integer('tiempo_residencia')->nullable();
                $table->string('movil');
                $table->boolean('activa_laboralmente_siniestro')->default(false);
                $table->string('trabajaba_empresa')->nullable();
                $table->string('ocupacion')->nullable();
                $table->decimal('salario', 10, 2)->nullable();
                $table->integer('tiempo_trabajo')->nullable();

                // Relaciones con cobertura de salud y tipo de afiliación
                $table->unsignedBigInteger('coberturas_salud');
                $table->unsignedBigInteger('tipos_afiliaciones');

                $table->string('regimen')->nullable();
                $table->boolean('estado_afiliacion')->default(false);
                $table->boolean('registra_beneficiarios_eps')->default(false);

                $table->timestamps();

                // Claves foráneas
                $table->foreign('cedula_numero')->references('cedula_numero')->on('afiliados')->onDelete('cascade');
                $table->foreign('reclamante')->references('id')->on('reclamantes')->onDelete('cascade');
                $table->foreign('tipo_documento')->references('id')->on('tipo_documentos')->onDelete('cascade');
                $table->foreign('ciudad_nacimiento')->references('id')->on('municipios')->onDelete('cascade');
                $table->foreign('departamento_nacimiento')->references('id')->on('departamentos')->onDelete('cascade');
                $table->foreign('ciudad_expedicion')->references('id')->on('municipios')->onDelete('cascade');
                $table->foreign('departamento_expedicion')->references('id')->on('departamentos')->onDelete('cascade');
                $table->foreign('ciudad')->references('id')->on('municipios')->onDelete('cascade');
                $table->foreign('estado_civil')->references('id')->on('estados_civiles')->onDelete('cascade');
                $table->foreign('coberturas_salud')->references('id')->on('coberturas_saluds')->onDelete('cascade');
                $table->foreign('tipos_afiliaciones')->references('id')->on('tipos_afiliaciones')->onDelete('cascade');
                $table->foreign('datos_basicos_hijo')->references('id')->on('datos_basicos_hijos')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclamante_afiliado');
    }
};
