<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MensajeDeBienvenida
 *
 * @property $id
 * @property $titulo
 * @property $descripcion
 * @property $logo
 * @property $rol
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class MensajeDeBienvenida extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['titulo', 'descripcion', 'logo', 'rol'];


}
