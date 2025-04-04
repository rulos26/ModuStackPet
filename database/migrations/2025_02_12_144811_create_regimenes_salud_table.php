<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('regimenes_saluds')) {
            Schema::create('regimenes_saluds', function (Blueprint $table) {
                $table->id();
                $table->string('nombre')->unique();
                $table->timestamps();
            });

           
        }
    }

    public function down(): void {
        Schema::dropIfExists('regimenes_saluds');
    }
};
