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
        Schema::table('users', function (Blueprint $table) {
            $table->string('tipo_documento')->nullable();
            $table->string('cedula')->nullable()->unique();
            $table->string('avatar')->nullable();
            $table->boolean('activo')->default(true);
            $table->string('telefono')->nullable();
            $table->string('whatsapp')->nullable();
            $table->date('fecha_nacimiento')->nullable(); // Paseador
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
