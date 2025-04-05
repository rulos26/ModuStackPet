<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $perPage = 20;

    protected $fillable = ['referencia', 'descripcion'];

    /**
     * Relación con el modelo Pedido.
     * Un Producto tiene muchos Pedidos.
     */
    public function pedidos()
    {
        return $this->hasMany(\App\Models\Pedido::class, 'producto', 'id'); // Clave foránea 'producto'
    }
}
