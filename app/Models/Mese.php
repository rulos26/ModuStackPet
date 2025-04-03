<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Mese
 *
 * @property $id
 * @property $nombre
 * @property $created_at
 * @property $updated_at
 *
 * @property Modustack.datosAhorro[] $modustack.datosAhorros
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Mese extends Model
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
    public function modustack.datosAhorros()
    {
        return $this->hasMany(\App\Models\Modustack.datosAhorro::class, 'id', 'mes_id');
    }
    
}
