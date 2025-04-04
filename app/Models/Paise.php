<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Paise
 *
 * @property $id
 * @property $name
 * @property $iso_name
 * @property $alfa2
 * @property $alfa3
 * @property $numerico
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Paise extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'iso_name', 'alfa2', 'alfa3', 'numerico'];


}
