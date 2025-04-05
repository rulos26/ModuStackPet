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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente'); // Clave foránea al modelo Cliente
            $table->unsignedBigInteger('producto'); // Clave foránea al modelo Producto
            $table->unsignedBigInteger('ciclo'); // Clave foránea al modelo Ciclo
            $table->unsignedBigInteger('estado_pedido'); // Clave foránea al modelo EstadosPedido
            $table->unsignedBigInteger('estado_deuda'); // Clave foránea al modelo EstadosDeuda
            $table->text('descripcion')->nullable();
            $table->decimal('valor_catalogo', 10, 2);
            $table->decimal('valor_lista', 10, 2);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
