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
        if (!Schema::hasTable('oauth_providers')) {
            Schema::create('oauth_providers', function (Blueprint $table) {
                $table->id();
                $table->string('provider')->unique(); // google, facebook, github, etc.
                $table->string('name'); // Nombre amigable: Google, Facebook, etc.
                $table->string('client_id')->nullable();
                $table->string('client_secret')->nullable();
                $table->string('redirect_uri')->nullable();
                $table->boolean('is_active')->default(false);
                $table->text('description')->nullable();
                $table->timestamps();
                
                $table->index('provider');
                $table->index('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth_providers');
    }
};

