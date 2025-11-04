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
        if (!Schema::hasTable('backup_configs')) {
            Schema::create('backup_configs', function (Blueprint $table) {
                $table->id();
                $table->string('name')->comment('Nombre descriptivo de la configuración');
                $table->string('connection')->default('mysql');
                $table->string('host');
                $table->integer('port')->default(3306);
                $table->string('database')->comment('Base de datos destino (NO puede ser la de producción)');
                $table->string('username');
                $table->string('password');
                $table->boolean('execute_seeders')->default(false)->comment('Ejecutar seeders después del backup');
                $table->boolean('is_active')->default(true);
                $table->text('last_backup_result')->nullable()->comment('Resultado del último backup en JSON');
                $table->timestamp('last_backup_at')->nullable();
                $table->timestamps();
                
                $table->index('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_configs');
    }
};

