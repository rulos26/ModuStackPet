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
        if (!Schema::hasTable('email_configs')) {
            Schema::create('email_configs', function (Blueprint $table) {
                $table->id();
                $table->string('mailer')->default('smtp');
                $table->string('host');
                $table->integer('port')->default(587);
                $table->string('username');
                $table->string('password');
                $table->string('encryption')->default('tls');
                $table->string('from_address');
                $table->string('from_name')->nullable();
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
        Schema::dropIfExists('email_configs');
    }
};

