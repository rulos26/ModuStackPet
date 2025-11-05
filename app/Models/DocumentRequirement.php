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
        
        // Si aplica solo para razas peligrosas, verificar si la raza es peligrosa
        // Esto requeriría un campo en la tabla razas o una configuración
        // Por ahora retornamos true si no es específico
        return true; // TODO: Implementar lógica de razas peligrosas
    }
}
