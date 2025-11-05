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
        if (!Schema::hasTable('mascotas')) {
            return;
        }

        Schema::table('mascotas', function (Blueprint $table) {
            // Eliminar índices primero
            if (Schema::hasColumn('mascotas', 'barrio_id')) {
                $table->dropIndex(['barrio_id']);
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

