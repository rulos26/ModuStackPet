<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MetodosAhorro
 *
 * @property $id
 * @property $nom_metodo
 * @property $descripcion
 * @property $created_at
 * @property $updated_at
 *
 * @property Modustack.porcentajesAhorro[] $modustack.porcentajesAhorros
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class MetodosAhorro extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nom_metodo', 'descripcion'];


   /**
     * Relación con el modelo PorcentajesAhorro.
     * Un método de ahorro puede tener varios porcentajes asociados.
     */
    public function porcentajes()
    {
        return $this->hasMany(PorcentajesAhorro::class, 'metodo_id');
    }
    
}
