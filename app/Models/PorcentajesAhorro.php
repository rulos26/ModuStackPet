<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PorcentajesAhorro extends Model
{
    protected $table = 'porcentajes_ahorros'; // Asegúrate de que coincide con tu migración

    protected $fillable = [
        'metodo_id', 
        'porcentaje_1', 
        'porcentaje_2', 
        'porcentaje_3', 
        'porcentaje_4'
    ];

    /**
     * Relación con el modelo MetodoAhorro.
     * Un porcentaje de ahorro pertenece a un método de ahorro.
     */
    public function metodo()
    {
        return $this->belongsTo(MetodosAhorro::class, 'metodo_id');
    }
}
