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
        if (!Schema::hasTable('paths_documentos')) {
            $hasUsers = Schema::hasTable('users');
            Schema::create('paths_documentos', function (Blueprint $table) use ($hasUsers) {
                $table->id();
                if ($hasUsers) {
                    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                } else {
                    $table->unsignedBigInteger('user_id');
                }
                $table->string('nombre_path');
                $table->boolean('estado')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paths_documentos');
    }
};
