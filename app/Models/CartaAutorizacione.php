<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CartaAutorizacione
 *
 * @property $id
 * @property $cedula
 * @property $firmado
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class CartaAutorizacione extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cedula', 'firmado'];


}
