<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Verificacione
 *
 * @property $id
 * @property $cedula_numero
 * @property $cedula_afiliado
 * @property $registro_civil_nacimiento_afiliado
 * @property $registro_defuncion_afiliado
 * @property $cedula_reclamante
 * @property $registro_civil_nacimiento_descendiente
 * @property $created_at
 * @property $updated_at
 *
 * @property Afiliado $afiliado
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Verificacione extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cedula_numero', 'cedula_afiliado', 'registro_civil_nacimiento_afiliado', 'registro_defuncion_afiliado', 'cedula_reclamante', 'registro_civil_nacimiento_descendiente'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function afiliado()
    {
        return $this->belongsTo(\App\Models\Afiliado::class, 'cedula_numero', 'cedula_numero');
    }
    
}
