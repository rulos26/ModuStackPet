<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $perPage = 20;

    protected $fillable = ['nombre'];

    /**
     * Relación con el modelo Pedido.
     * Un Cliente tiene muchos Pedidos.
     */
    public function pedidos()
    {
        return $this->hasMany(\App\Models\Pedido::class, 'cliente', 'id'); // Clave foránea 'cliente'
    }
}
