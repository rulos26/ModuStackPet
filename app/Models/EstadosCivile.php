<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EstadosCivile
 *
 * @property $id
 * @property $estado_civil
 * @property $created_at
 * @property $updated_at
 *
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
    protected $fillable = ['estado_civil'];


}
