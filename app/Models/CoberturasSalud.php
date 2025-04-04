<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CoberturasSalud
 *
 * @property $id
 * @property $cobertura
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class CoberturasSalud extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cobertura'];


}
