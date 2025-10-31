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
        Schema::table('clientes', function (Blueprint $table) {
            // Agregar user_id después de id
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
            $table->index('user_id');
            
            // Agregar campos adicionales para perfil completo
            $table->foreignId('tipo_documento_id')->nullable()->after('user_id')->constrained('tipo_documentos')->nullOnDelete();
            $table->string('cedula')->nullable()->after('tipo_documento_id');
            $table->string('telefono')->nullable()->after('cedula');
            $table->string('whatsapp')->nullable()->after('telefono');
            $table->date('fecha_nacimiento')->nullable()->after('whatsapp');
            $table->string('direccion')->nullable()->after('fecha_nacimiento');
            $table->foreignId('ciudad_id')->nullable()->after('direccion')->constrained('ciudades')->nullOnDelete();
            $table->foreignId('barrio_id')->nullable()->after('ciudad_id')->constrained('barrios')->nullOnDelete();
            $table->string('avatar')->nullable()->after('barrio_id');
        });
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
