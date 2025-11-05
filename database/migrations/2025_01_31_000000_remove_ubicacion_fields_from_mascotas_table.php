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
        if (!Schema::hasTable('mascotas')) {
            return;
        }

        // Primero eliminar la clave foránea si existe
        // Obtener el nombre real de la foreign key desde la base de datos
        if (Schema::hasColumn('mascotas', 'barrio_id')) {
            try {
                // Intentar obtener el nombre de la foreign key desde INFORMATION_SCHEMA
                $foreignKeyName = DB::selectOne("
                    SELECT CONSTRAINT_NAME 
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'mascotas' 
                    AND COLUMN_NAME = 'barrio_id' 
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ");

                if ($foreignKeyName && isset($foreignKeyName->CONSTRAINT_NAME)) {
                    // Eliminar la foreign key usando su nombre real
                    DB::statement("ALTER TABLE `mascotas` DROP FOREIGN KEY `{$foreignKeyName->CONSTRAINT_NAME}`");
                } else {
                    // Si no se encontró, intentar con el método de Laravel
                    Schema::table('mascotas', function (Blueprint $table) {
                        $table->dropForeign(['barrio_id']);
                    });
                }
            } catch (\Exception $e) {
                // Si falla, intentar con el nombre estándar que Laravel genera
                try {
                    DB::statement('ALTER TABLE `mascotas` DROP FOREIGN KEY `mascotas_barrio_id_foreign`');
                } catch (\Exception $e2) {
                    // Si no existe la foreign key, continuar
                }
            }
        }

        // Ahora eliminar índices y columnas
        Schema::table('mascotas', function (Blueprint $table) {
            // Eliminar índices después de eliminar la foreign key
            if (Schema::hasColumn('mascotas', 'barrio_id')) {
                // Intentar eliminar el índice si existe (puede tener diferentes nombres)
                try {
                    $table->dropIndex(['barrio_id']);
                } catch (\Exception $e) {
                    // Si el índice no existe o tiene otro nombre, intentar el nombre específico
                    try {
                        $table->dropIndex('mascotas_barrio_id_index');
                    } catch (\Exception $e2) {
                        // Ignorar si no existe
                    }
                }
            }

            // Eliminar columnas de ubicación (ya están en la tabla clientes)
            if (Schema::hasColumn('mascotas', 'barrio_id')) {
                $table->dropColumn('barrio_id');
            }
            if (Schema::hasColumn('mascotas', 'direccion')) {
                $table->dropColumn('direccion');
            }
            if (Schema::hasColumn('mascotas', 'interior_apto')) {
                $table->dropColumn('interior_apto');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('mascotas')) {
            return;
        }

        Schema::table('mascotas', function (Blueprint $table) {
            // Restaurar columnas si se necesita hacer rollback
            $table->string('direccion')->nullable()->after('comportamiento');
            $table->string('interior_apto')->nullable()->after('direccion');
            $table->unsignedBigInteger('barrio_id')->nullable()->after('interior_apto');
            
            // Restaurar índice
            $table->index('barrio_id');
        });
    }
};

