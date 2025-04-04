<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Siniestro
 *
 * @property $id
 * @property $cedula_numero
 * @property $fecha_siniestro
 * @property $lugar
 * @property $departamento
 * @property $municipio
 * @property $created_at
 * @property $updated_at
 *
 * @property Afiliado $afiliado
 * @property Departamento $departamento
 * @property Municipio $municipio
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Siniestro extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cedula_numero', 'fecha_siniestro', 'lugar', 'departamento', 'municipio'];


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
    public function departamento()
    {
        return $this->belongsTo(\App\Models\Departamento::class, 'nombre', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function municipio()
    {
        return $this->belongsTo(\App\Models\Municipio::class, 'nombre', 'id');
    }
    
}
