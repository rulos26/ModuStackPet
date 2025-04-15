<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Empresa
 *
 * @property $id
 * @property $nombre_legal
 * @property $nombre_comercial
 * @property $nit
 * @property $dv
 * @property $representante_legal
 * @property $tipo_empresa_id
 * @property $telefono
 * @property $email
 * @property $direccion
 * @property $ciudad_id
 * @property $departamento_id
 * @property $sector_id
 * @property $logo
 * @property $estado
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Ciudade $ciudade
 * @property Departamento $departamento
 * @property Sectore $sectore
 * @property TiposEmpresa $tiposEmpresa
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Empresa extends Model
{
    use SoftDeletes;

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre_legal', 'nombre_comercial', 'nit', 'dv', 'representante_legal', 'tipo_empresa_id', 'telefono', 'email', 'direccion', 'ciudad_id', 'departamento_id', 'sector_id', 'logo', 'estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ciudade()
    {
        return $this->belongsTo(\App\Models\Ciudade::class, 'ciudad_id', 'id_municipio');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departamento()
    {
        return $this->belongsTo(\App\Models\Departamento::class, 'departamento_id', 'id_departamento');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sectore()
    {
        return $this->belongsTo(\App\Models\Sectore::class, 'sector_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tiposEmpresa()
    {
        return $this->belongsTo(\App\Models\TiposEmpresa::class, 'tipo_empresa_id', 'id');
    }
    
}
