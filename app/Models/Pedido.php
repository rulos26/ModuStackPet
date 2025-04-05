<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pedido
 *
 * @property $id
 * @property $cliente
 * @property $producto
 * @property $ciclo
 * @property $estado_pedido
 * @property $estado_deuda
 * @property $descripcion
 * @property $valor_catalogo
 * @property $valor_lista
 * @property $created_at
 * @property $updated_at
 * @property Ciclo $ciclo
 * @property Cliente $cliente
 * @property EstadosDeuda $estadosDeuda
 * @property EstadosPedido $estadosPedido
 * @property Producto $producto
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Pedido extends Model
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cliente', 'producto', 'ciclo', 'estado_pedido', 'estado_deuda', 'descripcion', 'valor_catalogo', 'valor_lista'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    /*  public function ciclo()
     {
         return $this->belongsTo(\App\Models\Ciclo::class, 'ciclo', 'id');
     } */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    /* public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class, 'cliente', 'id'); // Clave foránea 'cliente'
    } */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estadosDeuda()
    {
        return $this->belongsTo(\App\Models\EstadosDeuda::class, 'estado_deuda', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estadosPedido()
    {
        return $this->belongsTo(\App\Models\EstadosPedido::class, 'estado_pedido', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    /* public function producto()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'producto', 'id');
    } */
}
