<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paseador extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'tipo_documento_id',
        'cedula',
        'telefono',
        'whatsapp',
        'fecha_nacimiento',
        'direccion',
        'ciudad_id',
        'barrio_id',
        'experiencia_anos',
        'calificacion_promedio',
        'disponibilidad',
        'tarifa_hora',
        'documentos_verificados',
        'avatar',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'documentos_verificados' => 'array',
            'disponibilidad' => 'boolean',
            'calificacion_promedio' => 'decimal:2',
            'tarifa_hora' => 'decimal:2',
            'fecha_nacimiento' => 'date',
        ];
    }

    /**
     * Relación: Un paseador pertenece a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un paseador tiene un tipo de documento
     */
    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }

    /**
     * Relación: Un paseador está en una ciudad
     */
    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class);
    }

    /**
     * Relación: Un paseador está en un barrio
     */
    public function barrio(): BelongsTo
    {
        return $this->belongsTo(Barrio::class);
    }
}
