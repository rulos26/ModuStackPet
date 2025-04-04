<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DireccionesVivienda
 *
 * @property $id
 * @property $cedula_numero
 * @property $direccion_residencia
 * @property $tipo_de_vivienda
 * @property $tipo_de_propiedad
 * @property $vive_desde
 * @property $created_at
 * @property $updated_at
 *
 * @property Afiliado $afiliado
 * @property TiposDePropiedade $tiposDePropiedade
 * @property TiposDeVivienda $tiposDeVivienda
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class DireccionesVivienda extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cedula_numero', 'direccion_residencia', 'tipo_de_vivienda', 'tipo_de_propiedad', 'vive_desde'];


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
    public function tiposDePropiedade()
    {
        return $this->belongsTo(\App\Models\TiposDePropiedade::class, 'tipo_de_propiedad', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tiposDeVivienda()
    {
        return $this->belongsTo(\App\Models\TiposDeVivienda::class, 'tipo_de_vivienda', 'id');
    }
    
}
