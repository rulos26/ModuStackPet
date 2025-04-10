<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Raza
 *
 * @property $id
 * @property $tipo_mascota
 * @property $nombre
 * @property $created_at
 * @property $updated_at
 *
 * @property Mascota[] $mascotas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Raza extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['tipo_mascota', 'nombre'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mascotas()
    {
        return $this->hasMany(\App\Models\Mascota::class, 'id', 'raza_id');
    }
    
}
