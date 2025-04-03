<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EstadosCivile
 *
 * @property $id
 * @property $nombre
 * @property $created_at
 * @property $updated_at
 *
 * @property Laravel.datosComplementario[] $laravel.datosComplementarios
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class EstadosCivile extends Model
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
    public function datosComplementarios()
    {
        //return $this->hasMany(\App\Models\Laravel.datosComplementario::class, 'id', 'estado_civil_id');
    }
    
}
