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
        if (!Schema::hasTable('database_configs')) {
            Schema::create('database_configs', function (Blueprint $table) {
                $table->id();
                $table->string('connection')->default('mysql');
                $table->string('host');
                $table->integer('port')->default(3306);
                $table->string('database');
                $table->string('username');
                $table->string('password');
                $table->boolean('is_active')->default(true);
                $table->text('last_test_result')->nullable();
                $table->timestamp('last_tested_at')->nullable();
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
        Schema::dropIfExists('database_configs');
    }
};

