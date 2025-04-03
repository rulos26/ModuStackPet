<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\datosComplementarios;

/**
 * Class Genero
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
class Genero extends Model
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
        //return $this->hasMany(datosComplementarios::class, 'id', 'genero_id');
    }
    
}
