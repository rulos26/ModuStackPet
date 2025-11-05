<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MascotaDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'mascota_id',
        'document_requirement_id',
        'nombre_archivo',
        'ruta_archivo',
        'tipo_mime',
        'tamaño_bytes',
        'hash_archivo',
        'estado',
        'motivo_rechazo',
        'fecha_emision',
        'fecha_vencimiento',
        'validacion_automatica',
        'detalles_validacion',
        'usuario_subio_id',
        'usuario_aprobo_id',
        'fecha_aprobacion',
        'notas',
    ];

    protected $casts = [
        'tamaño_bytes' => 'integer',
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'validacion_automatica' => 'boolean',
        'detalles_validacion' => 'array',
        'fecha_aprobacion' => 'datetime',
    ];

    /**
     * Relación: Un documento pertenece a una mascota
     */
    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }

    /**
     * Relación: Un documento pertenece a un requisito
     */
    public function documentRequirement()
    {
        return $this->belongsTo(DocumentRequirement::class);
    }

    /**
     * Relación: Usuario que subió el documento
     */
    public function usuarioSubio()
    {
        return $this->belongsTo(User::class, 'usuario_subio_id');
    }

    /**
     * Relación: Usuario que aprobó el documento
     */
    public function usuarioAprobo()
    {
        return $this->belongsTo(User::class, 'usuario_aprobo_id');
    }

    /**
     * Scope: Documentos aprobados
     */
    public function scopeAprobados($query)
    {
        return $query->where('estado', 'aprobado');
    }

    /**
     * Scope: Documentos pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope: Documentos rechazados
     */
    public function scopeRechazados($query)
    {
        return $query->where('estado', 'rechazado');
    }

    /**
     * Obtener la URL del archivo
     */
    public function getUrlAttribute()
    {
        if ($this->ruta_archivo && Storage::disk('public')->exists($this->ruta_archivo)) {
            return Storage::disk('public')->url($this->ruta_archivo);
        }
        return null;
    }

    /**
     * Verificar si el documento está vencido
     */
    public function estaVencido()
    {
        if (!$this->fecha_vencimiento) {
            return false;
        }
        return $this->fecha_vencimiento->isPast();
    }

    /**
     * Verificar si el documento está próximo a vencer (30 días)
     */
    public function proximoAVencer($dias = 30)
    {
        if (!$this->fecha_vencimiento) {
            return false;
        }
        return $this->fecha_vencimiento->isFuture() 
            && $this->fecha_vencimiento->diffInDays(now()) <= $dias;
    }
}
