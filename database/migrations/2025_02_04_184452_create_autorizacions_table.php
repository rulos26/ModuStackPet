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
        Schema::create('autorizacions', function (Blueprint $table) {
            $table->id();
            $table->string('cedula', 20);
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('foto_perfil_path');
            $table->string('foto_cedula_path');
            $table->string('foto_firma_path');
            $table->string('direccion')->nullable();
            $table->string('barrio')->nullable();
            $table->string('localidad')->nullable();
            $table->string('telefono_fijo')->nullable();
            $table->string('celular')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autorizacions');
    }
};
