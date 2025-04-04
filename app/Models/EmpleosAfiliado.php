<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EmpleosAfiliado
 *
 * @property $id
 * @property $cedula_numero
 * @property $afiliado_trabaja
 * @property $empresa
 * @property $cargo
 * @property $tiempo
 * @property $salario
 * @property $no_telefonico
 * @property $created_at
 * @property $updated_at
 *
 * @property Afiliado $afiliado
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class EmpleosAfiliado extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cedula_numero', 'afiliado_trabaja', 'empresa', 'cargo', 'tiempo', 'salario', 'no_telefonico'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function afiliado()
    {
        return $this->belongsTo(\App\Models\Afiliado::class, 'cedula_numero', 'cedula_numero');
    }
    
}
