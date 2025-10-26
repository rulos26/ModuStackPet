<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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
    use HasFactory, SoftDeletes;

    /**
     * Nombre de la tabla asociada al modelo
     */
    protected $table = 'empresas';

    /**
     * Atributos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'nombre_legal',
        'nombre_comercial',
        'nit',
        'dv',
        'representante_legal',
        'tipo_empresa_id',
        'telefono',
        'email',
        'direccion',
        'ciudad_id',
        'departamento_id',
        'sector_id',
        'logo',
        'estado'
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'estado' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Atributos que deben ser ocultos en las respuestas JSON
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Relación con el tipo de empresa
     */
    public function tipoEmpresa()
    {
        return $this->belongsTo(TipoEmpresa::class, 'tipo_empresa_id');
    }

    /**
     * Relación con la ciudad
     */
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id', 'id_municipio');
    }

    /**
     * Relación con el departamento
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'id_departamento');
    }

    /**
     * Relación con el sector
     */
    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    /**
     * Accesor para obtener la URL completa del logo
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return asset('images/default-logo.png');
    }

    /**
     * Accesor para obtener el nombre completo de la empresa
     */
    public function getNombreCompletoAttribute()
    {
        if ($this->nombre_comercial) {
            return "{$this->nombre_legal} ({$this->nombre_comercial})";
        }
        return $this->nombre_legal;
    }

    /**
     * Accesor para obtener el NIT completo con dígito de verificación
     */
    public function getNitCompletoAttribute()
    {
        if ($this->dv) {
            return "{$this->nit}-{$this->dv}";
        }
        return $this->nit;
    }

    /**
     * Scope para obtener solo las empresas activas.
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 1);
    }

    /**
     * Scope para filtrar empresas por departamento
     */
    public function scopePorDepartamento($query, $departamentoId)
    {
        return $query->where('departamento_id', $departamentoId);
    }

    /**
     * Scope para filtrar empresas por sector
     */
    public function scopePorSector($query, $sectorId)
    {
        return $query->where('sector_id', $sectorId);
    }

    /**
     * Scope para buscar empresas por nombre o NIT
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('nombre_legal', 'like', "%{$termino}%")
              ->orWhere('nombre_comercial', 'like', "%{$termino}%")
              ->orWhere('nit', 'like', "%{$termino}%");
        });
    }

    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();

        // Evento para eliminar logo al eliminar empresa
        static::deleting(function ($empresa) {
            if ($empresa->logo && Storage::disk('public')->exists($empresa->logo)) {
                Storage::disk('public')->delete($empresa->logo);
            }
        });
    }
}
