<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AfiliadosConvivencia
 *
 * @property $id
 * @property $cedula_numero
 * @property $estado_civil_al_siniestro
 * @property $desde_estado_civil
 * @property $hasta_estado_civil
 * @property $relacion_con
 * @property $quien_convivía
 * @property $tiempo_convivencia
 * @property $desde_convivencia
 * @property $hasta_convivencia
 * @property $created_at
 * @property $updated_at
 *
 * @property Afiliado $afiliado
 * @property EstadosCivile $estadosCivile
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class AfiliadosConvivencia extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cedula_numero', 'estado_civil_al_siniestro', 'desde_estado_civil', 'hasta_estado_civil', 'relacion_con', 'quien_convivía', 'tiempo_convivencia', 'desde_convivencia', 'hasta_convivencia'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function afiliado()
    {
        return $this->belongsTo(\App\Models\Afiliado::class, 'cedula_numero', 'cedula_numero');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estadosCivile()
    {
        return $this->belongsTo(\App\Models\EstadosCivile::class, 'estado_civil_al_siniestro', 'id');
    }
    
}
