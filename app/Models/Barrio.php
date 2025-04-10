<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Barrio
 *
 * @property $id
 * @property $nombre
 * @property $localidad
 * @property $created_at
 * @property $updated_at
 *
 * @property Mascota[] $mascotas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Barrio extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre', 'localidad'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mascotas()
    {
        return $this->hasMany(\App\Models\Mascota::class, 'id', 'barrio_id');
    }
    
}
