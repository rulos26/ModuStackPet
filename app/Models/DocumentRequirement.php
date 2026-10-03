<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentRequirement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'obligatorio',
        'activo',
        'orden',
        'tipo_validacion',
        'dias_validez',
        'formatos_permitidos',
        'tamaño_maximo_kb',
        'aplica_razas_peligrosas',
    ];

    protected $casts = [
        'obligatorio' => 'boolean',
        'activo' => 'boolean',
        'orden' => 'integer',
        'dias_validez' => 'integer',
        'formatos_permitidos' => 'array',
        'tamaño_maximo_kb' => 'integer',
        'aplica_razas_peligrosas' => 'boolean',
    ];

    /**
     * Relación: Un requisito tiene muchos logs
     */
    public function logs()
    {
        return $this->hasMany(DocumentRequirementLog::class);
    }

    /**
     * Relación: Un requisito tiene muchos documentos subidos
     */
    public function mascotaDocuments()
    {
        return $this->hasMany(MascotaDocument::class);
    }

    /**
     * Scope: Requisitos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope: Requisitos obligatorios
     */
    public function scopeObligatorios($query)
    {
        return $query->where('obligatorio', true);
    }

    /**
     * Scope: Ordenados por orden
     */
    public function scopeOrdenados($query)
    {
        return $query->orderBy('orden')->orderBy('nombre');
    }

    /**
     * Verificar si aplica para una raza
     */
    public function aplicaParaRaza($raza)
    {
        // Si no aplica solo para razas peligrosas, aplica para todas
        if (!$this->aplica_razas_peligrosas) {
            return true;
        }
        
        // El nombre del campo es legado. La decisión de producto vigente no
        // clasifica razas como peligrosas: identifica las que requieren cuidados
        // especiales de manejo (bozal, correa corta, etc.).
        return (bool) ($raza?->requiere_cuidado_especial ?? false);
    }
}
