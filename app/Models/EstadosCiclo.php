<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadosCiclo extends Model
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre'];

    /**
     * Relación con el modelo Ciclo.
     * Un EstadoCiclo puede tener múltiples Ciclos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ciclos()
    {
        return $this->hasMany(\App\Models\Ciclo::class, 'estado', 'id');
    }
}
