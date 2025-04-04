<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TiposDePropiedade
 *
 * @property $id
 * @property $nombre
 * @property $created_at
 * @property $updated_at
 *
 * @property DireccionesVivienda[] $direccionesViviendas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TiposDePropiedade extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function direccionesViviendas()
    {
        return $this->hasMany(\App\Models\DireccionesVivienda::class, 'id', 'tipo_de_propiedad');
    }
    
}
