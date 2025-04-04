<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Amparo
 *
 * @property $id
 * @property $nombre
 * @property $created_at
 * @property $updated_at
 *
 * @property DatosBasico[] $datosBasicos
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Amparo extends Model
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
    public function datosBasicos()
    {
        return $this->hasMany(\App\Models\DatosBasico::class, 'id', 'amparo');
    }
    
}
