<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Mascota
 *
 * @property $id
 * @property $user_id
 * @property $avatar
 * @property $nombre
 * @property $edad
 * @property $fecha_nacimiento
 * @property $raza_id
 * @property $genero
 * @property $vacunas_completas
 * @property $ultima_vacunacion
 * @property $comportamiento
 * @property $direccion
 * @property $interior_apto
 * @property $barrio_id
 * @property $recomendaciones
 * @property $esterilizado
 * @property $enfermedades
 * @property $ultimo_examen_medico
 * @property $created_at
 * @property $updated_at
 *
 * @property Barrio $barrio
 * @property Raza $raza
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Mascota extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'avatar', 'nombre', 'edad', 'fecha_nacimiento', 'raza_id', 'genero', 'vacunas_completas', 'ultima_vacunacion', 'comportamiento', 'recomendaciones', 'esterilizado', 'enfermedades', 'ultimo_examen_medico'];


    // Relación con barrio eliminada - la ubicación se obtiene del cliente

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function raza()
    {
        return $this->belongsTo(\App\Models\Raza::class, 'raza_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function vacunasCertificaciones()
    {
        return $this->hasMany(VacunaCertificacion::class, 'id_mascota');
    }
}
