<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TiposAfiliacione
 *
 * @property $id
 * @property $tipo_afiliacion
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TiposAfiliacione extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['tipo_afiliacion'];


}
