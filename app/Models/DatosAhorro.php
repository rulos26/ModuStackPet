<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DatosAhorro
 *
 * @property $id
 * @property $user_id
 * @property $sueldo
 * @property $metodo_ahorro_id
 * @property $fecha_inicio
 * @property $fecha_fin
 * @property $mes_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Mese $mese
 * @property MetodosAhorro $metodosAhorro
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class DatosAhorro extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'sueldo', 'metodo_ahorro_id', 'fecha_inicio', 'fecha_fin', 'mes_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mese()
    {
        return $this->belongsTo(\App\Models\Mese::class, 'mes_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function metodosAhorro()
    {
        return $this->belongsTo(\App\Models\MetodosAhorro::class, 'metodo_ahorro_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
    
}
