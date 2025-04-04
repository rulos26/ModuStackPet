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
        if(! Schema::hasTable('paises')) {
        Schema::create('paises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('iso_name');
            $table->string('alfa2');
            $table->string('alfa3');
            $table->integer('numerico');
            $table->timestamps();
        });}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('paises');
    }
};
