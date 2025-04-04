<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FiltrosPdf
 *
 * @property $id
 * @property $nombre_modulo
 * @property $visible
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class FiltrosPdf extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre_modulo', 'visible'];


}
