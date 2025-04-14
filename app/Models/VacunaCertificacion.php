<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacunaCertificacion extends Model
{
    use HasFactory;

    protected $table = 'vacunas_certificaciones';

    protected $fillable = [
        'id_mascota',
        'fecha_ultima_vacuna',
        'operaciones',
        'certificado_veterinario',
        'cedula_propietario'
    ];

    protected $casts = [
        'fecha_ultima_vacuna' => 'date',
    ];

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'id_mascota');
    }
}
