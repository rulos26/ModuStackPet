<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciclo extends Model
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['numero_ciclo', 'fecha_inicio', 'fecha_fin', 'estado'];

    /**
     * Relación con el modelo EstadosCiclo.
     * Un Ciclo pertenece a un EstadoCiclo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estadoCiclo()
    {
        return $this->belongsTo(\App\Models\EstadosCiclo::class, 'estado', 'id');
    }
}
