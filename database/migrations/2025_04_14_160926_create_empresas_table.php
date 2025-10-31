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
        if (!Schema::hasTable('empresas')) {
            $hasTiposEmpresas = Schema::hasTable('tipos_empresas');
            $hasCiudades = Schema::hasTable('ciudades');
            $hasDepartamentos = Schema::hasTable('departamentos');
            $hasSectores = Schema::hasTable('sectores');
            
            Schema::create('empresas', function (Blueprint $table) use ($hasTiposEmpresas, $hasCiudades, $hasDepartamentos, $hasSectores) {
                $table->id();
                $table->string('nombre_legal', 100);
                $table->string('nombre_comercial', 100)->nullable();
                $table->string('nit', 20)->unique();
                $table->string('dv', 1)->nullable(); // Dígito de verificación
                $table->string('representante_legal', 255);
                if ($hasTiposEmpresas) {
                    $table->foreignId('tipo_empresa_id')->constrained('tipos_empresas')->onDelete('restrict');
                } else {
                    $table->unsignedBigInteger('tipo_empresa_id');
                }
                $table->string('telefono', 20)->nullable();
                $table->string('email', 100)->nullable();
                $table->string('direccion', 200)->nullable();
                $table->unsignedBigInteger('ciudad_id');
                $table->unsignedBigInteger('departamento_id');
                if ($hasSectores) {
                    $table->foreignId('sector_id')->constrained('sectores')->onDelete('restrict');
                } else {
                    $table->unsignedBigInteger('sector_id');
                }
                $table->string('logo', 255)->nullable();
                $table->tinyInteger('estado')->default(1);
                $table->timestamps();
                $table->softDeletes();

                // Índices para mejorar el rendimiento
                $table->index('nombre_legal');
                $table->index('nombre_comercial');
                $table->index('nit');
                $table->index('estado');
            });
            
            // Agregar foreign keys después de crear la tabla
            if ($hasCiudades) {
                try {
                    Schema::table('empresas', function (Blueprint $table) {
                        $table->foreign('ciudad_id')->references('id_municipio')->on('ciudades')->onDelete('restrict');
                    });
                } catch (\Exception $e) {
                    // Foreign key ya existe
                }
            }
            if ($hasDepartamentos) {
                try {
                    Schema::table('empresas', function (Blueprint $table) {
                        $table->foreign('departamento_id')->references('id_departamento')->on('departamentos')->onDelete('restrict');
                    });
                } catch (\Exception $e) {
                    // Foreign key ya existe
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
