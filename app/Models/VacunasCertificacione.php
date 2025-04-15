<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacunasCertificacione extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_mascota',
        'fecha_ultima_vacuna',
        'operaciones',
        'certificado_veterinario',
        'cedula_propietario'
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_ultima_vacuna' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtiene la mascota asociada al registro de vacunas y certificaciones.
     */
    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'id_mascota');
    }
}
