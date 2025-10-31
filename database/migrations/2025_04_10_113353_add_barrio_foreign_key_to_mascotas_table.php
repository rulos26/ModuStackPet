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
        if (Schema::hasTable('mascotas') && Schema::hasTable('barrios')) {
            try {
                Schema::table('mascotas', function (Blueprint $table) {
                    $table->foreign('barrio_id')
                          ->references('id')
                          ->on('barrios')
                          ->onDelete('set null');
                });
            } catch (\Exception $e) {
                // La foreign key ya existe o hay un error, ignorar
                // Esto puede pasar si la migración ya se ejecutó parcialmente
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mascotas', function (Blueprint $table) {
            $table->dropForeign(['barrio_id']);
        });
    }
};
