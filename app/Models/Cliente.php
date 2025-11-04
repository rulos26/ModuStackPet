<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nombre',
        'tipo_documento_id',
        'cedula',
        'telefono',
        'whatsapp',
        'fecha_nacimiento',
        'direccion',
        'ciudad_id',
        'barrio_id',
        'avatar',
        'latitud',
        'longitud',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'latitud' => 'decimal:8',
            'longitud' => 'decimal:8',
        ];
    }

    /**
     * Relación: Un cliente pertenece a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un cliente tiene un tipo de documento
     */
    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }

    /**
     * Relación: Un cliente está en una ciudad
     */
    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class);
    }

    /**
     * Relación: Un cliente está en un barrio
     */
    public function barrio(): BelongsTo
    {
        return $this->belongsTo(Barrio::class);
    }

    /**
     * Relación: Un cliente puede tener múltiples mascotas
     */
    public function mascotas(): HasMany
    {
        return $this->hasMany(Mascota::class, 'user_id', 'user_id');
    }
}
