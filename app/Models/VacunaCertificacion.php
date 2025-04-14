<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VacunaCertificacion extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos
     */
    protected $table = 'vacunas_certificaciones';

    /**
     * Atributos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'mascota_id',
        'fecha_ultima_vacuna',
        'operaciones',
        'certificado_veterinario',
        'cedula_propietario'
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'fecha_ultima_vacuna' => 'date',
    ];

    /**
     * Relación con el modelo Mascota
     * Una vacuna/certificación pertenece a una mascota
     */
    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class);
    }

    /**
     * Accesor para obtener la URL del certificado veterinario
     */
    public function getCertificadoVeterinarioUrlAttribute(): ?string
    {
        return $this->certificado_veterinario ? asset('storage/' . $this->certificado_veterinario) : null;
    }

    /**
     * Accesor para obtener la URL de la cédula del propietario
     */
    public function getCedulaPropietarioUrlAttribute(): ?string
    {
        return $this->cedula_propietario ? asset('storage/' . $this->cedula_propietario) : null;
    }

    /**
     * Accesor para formatear la fecha de la última vacuna
     */
    public function getFechaUltimaVacunaFormateadaAttribute(): ?string
    {
        return $this->fecha_ultima_vacuna ? $this->fecha_ultima_vacuna->format('d/m/Y') : null;
    }
}
