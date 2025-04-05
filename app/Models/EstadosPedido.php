<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadosPedido extends Model
{
    protected $perPage = 20;

    protected $fillable = ['nombre'];

    /**
     * Relación con el modelo Pedido.
     * Un EstadoPedido tiene muchos Pedidos.
     */
    public function pedidos()
    {
        return $this->hasMany(\App\Models\Pedido::class, 'estado_pedido', 'id'); // Clave foránea 'estado_pedido'
    }
}
