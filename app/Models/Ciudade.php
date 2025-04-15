<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Ciudad
 *
 * Representa una ciudad en el sistema
 *
 * @property int $id
 * @property string $nombre
 * @property int $departamento_id
 * @property bool $activo
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $deleted_at
 *
 * @property \App\Models\Departamento $departamento
 * @property \Illuminate\Database\Eloquent\Collection $barrios
 * @property \Illuminate\Database\Eloquent\Collection $mascotas
 * @property \Illuminate\Database\Eloquent\Collection $clientes
 * @property \Illuminate\Database\Eloquent\Collection $paseadores
 */
class Ciudade extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ciudades';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_municipio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'municipio',
        'estado',
        'departamento_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'estado' => 'integer',
        'departamento_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Obtiene el departamento al que pertenece la ciudad.
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }
}
