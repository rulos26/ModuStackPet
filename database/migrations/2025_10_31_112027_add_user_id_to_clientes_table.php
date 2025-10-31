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

        // Verificar y agregar user_id
        if (!Schema::hasColumn('clientes', 'user_id')) {
            Schema::table('clientes', function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
                $table->index('user_id');
            });
        }

        // Verificar y agregar campos adicionales uno por uno
        $hasTipoDocumentos = Schema::hasTable('tipo_documentos');
        $hasCiudades = Schema::hasTable('ciudades');
        $hasBarrios = Schema::hasTable('barrios');
        
        if (!Schema::hasColumn('clientes', 'tipo_documento_id')) {
            Schema::table('clientes', function (Blueprint $table) use ($hasTipoDocumentos) {
                if ($hasTipoDocumentos) {
                    $table->foreignId('tipo_documento_id')->nullable()->after('user_id')->constrained('tipo_documentos')->nullOnDelete();
                } else {
                    $table->unsignedBigInteger('tipo_documento_id')->nullable()->after('user_id');
                }
            });
        }
        
        if (!Schema::hasColumn('clientes', 'cedula')) {
            Schema::table('clientes', function (Blueprint $table) {
                $table->string('cedula')->nullable()->after('tipo_documento_id');
            });
        }
        
        if (!Schema::hasColumn('clientes', 'telefono')) {
            Schema::table('clientes', function (Blueprint $table) {
                $table->string('telefono')->nullable()->after('cedula');
            });
        }
        
        if (!Schema::hasColumn('clientes', 'whatsapp')) {
            Schema::table('clientes', function (Blueprint $table) {
                $table->string('whatsapp')->nullable()->after('telefono');
            });
        }
        
        if (!Schema::hasColumn('clientes', 'fecha_nacimiento')) {
            Schema::table('clientes', function (Blueprint $table) {
                $table->date('fecha_nacimiento')->nullable()->after('whatsapp');
            });
        }
        
        if (!Schema::hasColumn('clientes', 'direccion')) {
            Schema::table('clientes', function (Blueprint $table) {
                $table->string('direccion')->nullable()->after('fecha_nacimiento');
            });
        }
        
        if (!Schema::hasColumn('clientes', 'ciudad_id')) {
            Schema::table('clientes', function (Blueprint $table) {
                // ciudad_id debe referenciar id_municipio en ciudades, no id
                $table->unsignedBigInteger('ciudad_id')->nullable()->after('direccion');
            });
            
            // Agregar foreign key de ciudad_id después (referencia id_municipio)
            if ($hasCiudades) {
                try {
                    Schema::table('clientes', function (Blueprint $table) {
                        $table->foreign('ciudad_id')
                              ->references('id_municipio')
                              ->on('ciudades')
                              ->nullOnDelete();
                    });
                } catch (\Exception $e) {
                    // Foreign key ya existe
                }
            }
        }
        
        if (!Schema::hasColumn('clientes', 'barrio_id')) {
            Schema::table('clientes', function (Blueprint $table) use ($hasBarrios) {
                if ($hasBarrios) {
                    $table->foreignId('barrio_id')->nullable()->after('ciudad_id')->constrained('barrios')->nullOnDelete();
                } else {
                    $table->unsignedBigInteger('barrio_id')->nullable()->after('ciudad_id');
                }
            });
        }
        
        if (!Schema::hasColumn('clientes', 'avatar')) {
            Schema::table('clientes', function (Blueprint $table) {
                $table->string('avatar')->nullable()->after('barrio_id');
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
            $table->dropIndex(['user_id']);
            
            // Eliminar foreign keys
            $table->dropForeign(['user_id']);
            $table->dropForeign(['tipo_documento_id']);
            $table->dropForeign(['ciudad_id']);
            $table->dropForeign(['barrio_id']);
            
            // Eliminar columnas
            $table->dropColumn([
                'user_id',
                'tipo_documento_id',
                'cedula',
                'telefono',
                'whatsapp',
                'fecha_nacimiento',
                'direccion',
                'ciudad_id',
                'barrio_id',
                'avatar',
            ]);
        });
    }
};
