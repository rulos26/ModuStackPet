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
        if (!Schema::hasTable('clientes')) {
            return; // Si la tabla no existe, no hacer nada
        }

        Schema::table('clientes', function (Blueprint $table) {
            // Agregar campo nombre del conjunto/cerrado (no obligatorio)
            if (!Schema::hasColumn('clientes', 'nombre_conjunto_cerrado')) {
                $table->string('nombre_conjunto_cerrado', 255)->nullable()->after('direccion');
            }
            
            // Agregar campo interior/apartamento (no obligatorio)
            if (!Schema::hasColumn('clientes', 'interior_apartamento')) {
                $table->string('interior_apartamento', 100)->nullable()->after('nombre_conjunto_cerrado');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Eliminar columnas
            if (Schema::hasColumn('clientes', 'interior_apartamento')) {
                $table->dropColumn('interior_apartamento');
            }
            
            if (Schema::hasColumn('clientes', 'nombre_conjunto_cerrado')) {
                $table->dropColumn('nombre_conjunto_cerrado');
            }
        });
    }
};
