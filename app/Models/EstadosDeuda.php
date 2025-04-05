<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadosDeuda extends Model
{
    protected $perPage = 20;

    protected $fillable = ['nombre'];

    /**
     * Relación con el modelo Pedido.
     * Un EstadoDeuda tiene muchos Pedidos.
     */
    public function pedidos()
    {
        return $this->hasMany(\App\Models\Pedido::class, 'estado_deuda', 'id'); // Clave foránea 'estado_deuda'
    }
}
