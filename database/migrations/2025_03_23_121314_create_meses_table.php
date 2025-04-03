<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meses', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 20)->unique(); // "Enero", "Febrero"...
            $table->timestamps();
        });

         // Insertar los 12 meses por defecto
         DB::table('meses')->insert([
            ['nombre' => 'Enero'], ['nombre' => 'Febrero'], ['nombre' => 'Marzo'],
            ['nombre' => 'Abril'], ['nombre' => 'Mayo'], ['nombre' => 'Junio'],
            ['nombre' => 'Julio'], ['nombre' => 'Agosto'], ['nombre' => 'Septiembre'],
            ['nombre' => 'Octubre'], ['nombre' => 'Noviembre'], ['nombre' => 'Diciembre'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meses');
    }
};
