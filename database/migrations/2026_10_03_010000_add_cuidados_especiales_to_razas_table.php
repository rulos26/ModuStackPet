<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('razas', function (Blueprint $table) {
            $table->boolean('requiere_cuidado_especial')->default(false)->after('nombre');
            $table->text('cuidados_especiales')->nullable()->after('requiere_cuidado_especial');
        });
    }

    public function down(): void
    {
        Schema::table('razas', function (Blueprint $table) {
            $table->dropColumn(['requiere_cuidado_especial', 'cuidados_especiales']);
        });
    }
};

