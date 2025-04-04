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
        // Verificamos si la tabla ya existe antes de crearla
        if (!Schema::hasTable('empleos_afiliados')) {
            Schema::create('empleos_afiliados', function (Blueprint $table) {
                $table->id(); // ID auto incremental para la tabla
                $table->string('cedula_numero'); // Clave foránea a la tabla afiliados (cedula_numero)
                $table->boolean('afiliado_trabaja'); // Indica si el afiliado trabaja (0 = No, 1 = Sí)
                $table->string('empresa')->nullable(); // Nombre de la empresa
                $table->string('cargo')->nullable(); // Cargo en la empresa
                $table->string('tiempo')->nullable(); // Tiempo de empleo (puede ser en años o meses)
                $table->decimal('salario', 10, 2)->nullable(); // Salario del afiliado
                $table->string('no_telefonico')->nullable(); // Número telefónico de contacto
                $table->timestamps(); // Registra las fechas de creación y actualización
        
                // Clave foránea
                $table->foreign('cedula_numero')->references('cedula_numero')->on('afiliados')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Comprobamos si la tabla existe antes de eliminarla
        if (Schema::hasTable('empleos_afiliados')) {
            Schema::dropIfExists('empleos_afiliados');
        }
    }
};
