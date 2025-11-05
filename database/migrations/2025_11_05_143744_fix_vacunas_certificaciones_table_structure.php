<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('vacunas_certificaciones')) {
            // Si la tabla no existe, crearla con la estructura correcta
            $hasMascotas = Schema::hasTable('mascotas');
            Schema::create('vacunas_certificaciones', function (Blueprint $table) use ($hasMascotas) {
                $table->id();
                if ($hasMascotas) {
                    $table->foreignId('id_mascota')->constrained('mascotas')->onDelete('cascade');
                } else {
                    $table->unsignedBigInteger('id_mascota');
                }
                $table->date('fecha_ultima_vacuna');
                $table->text('operaciones')->nullable();
                $table->string('certificado_veterinario')->nullable();
                $table->string('cedula_propietario')->nullable();
                $table->timestamps();
            });
        } else {
            // Si la tabla existe, modificar su estructura
            Schema::table('vacunas_certificaciones', function (Blueprint $table) {
                // Eliminar campos antiguos si existen
                if (Schema::hasColumn('vacunas_certificaciones', 'nombre')) {
                    $table->dropColumn('nombre');
                }
                if (Schema::hasColumn('vacunas_certificaciones', 'tipo')) {
                    $table->dropColumn('tipo');
                }
                if (Schema::hasColumn('vacunas_certificaciones', 'fecha_vencimiento')) {
                    $table->dropColumn('fecha_vencimiento');
                }
                if (Schema::hasColumn('vacunas_certificaciones', 'archivo')) {
                    $table->dropColumn('archivo');
                }
                if (Schema::hasColumn('vacunas_certificaciones', 'mascota_id')) {
                    // Primero eliminar la foreign key si existe
                    try {
                        $table->dropForeign(['mascota_id']);
                    } catch (\Exception $e) {
                        // Ignorar si no existe
                    }
                    $table->dropColumn('mascota_id');
                }
                
                // Eliminar soft deletes si existe
                if (Schema::hasColumn('vacunas_certificaciones', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
                
                // Agregar campos correctos si no existen
                if (!Schema::hasColumn('vacunas_certificaciones', 'id_mascota')) {
                    $hasMascotas = Schema::hasTable('mascotas');
                    if ($hasMascotas) {
                        $table->foreignId('id_mascota')->after('id')->constrained('mascotas')->onDelete('cascade');
                    } else {
                        $table->unsignedBigInteger('id_mascota')->after('id');
                    }
                }
                
                if (!Schema::hasColumn('vacunas_certificaciones', 'fecha_ultima_vacuna')) {
                    $table->date('fecha_ultima_vacuna')->after('id_mascota');
                }
                
                if (!Schema::hasColumn('vacunas_certificaciones', 'operaciones')) {
                    $table->text('operaciones')->nullable()->after('fecha_ultima_vacuna');
                }
                
                if (!Schema::hasColumn('vacunas_certificaciones', 'certificado_veterinario')) {
                    $table->string('certificado_veterinario')->nullable()->after('operaciones');
                }
                
                if (!Schema::hasColumn('vacunas_certificaciones', 'cedula_propietario')) {
                    $table->string('cedula_propietario')->nullable()->after('certificado_veterinario');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hacer nada en el down para evitar pérdida de datos
        // Si se necesita revertir, se debe hacer manualmente
    }
};
