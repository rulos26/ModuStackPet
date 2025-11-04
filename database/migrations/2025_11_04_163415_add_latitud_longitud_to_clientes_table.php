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
            // Verificar si las columnas ya existen antes de agregarlas
            if (!Schema::hasColumn('clientes', 'latitud')) {
                $table->decimal('latitud', 10, 8)->nullable()->after('direccion');
            }
            
            if (!Schema::hasColumn('clientes', 'longitud')) {
                $table->decimal('longitud', 11, 8)->nullable()->after('latitud');
            }
        });

        // Agregar índice después de crear las columnas
        if (Schema::hasColumn('clientes', 'latitud') && Schema::hasColumn('clientes', 'longitud')) {
            Schema::table('clientes', function (Blueprint $table) {
                $table->index(['latitud', 'longitud'], 'idx_clientes_coordenadas');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Eliminar índices primero
            if (Schema::hasColumn('clientes', 'latitud') && Schema::hasColumn('clientes', 'longitud')) {
                $table->dropIndex('idx_clientes_coordenadas');
            }
            
            // Eliminar columnas
            if (Schema::hasColumn('clientes', 'longitud')) {
                $table->dropColumn('longitud');
            }
            
            if (Schema::hasColumn('clientes', 'latitud')) {
                $table->dropColumn('latitud');
            }
        });
    }
};
