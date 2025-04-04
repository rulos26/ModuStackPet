<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PersonasAfiliada
 *
 * @property $id
 * @property $nombres_apellidos
 * @property $cedula
 * @property $edad
 * @property $fecha_nacimiento
 * @property $lugar_nacimiento_id
 * @property $fecha_siniestro
 * @property $lugar_siniestro_id
 * @property $estado_civil_siniestro_id
 * @property $estado_civil_desde
 * @property $estado_civil_hasta
 * @property $hijos
 * @property $edad_hijos
 * @property $relacion_con
 * @property $convivencia_con
 * @property $tiempo_convivencia
 * @property $direccion_residencia
 * @property $titular_trabaja
 * @property $empresa
 * @property $cargo
 * @property $tiempo_laboral
 * @property $salario
 * @property $telefono
 * @property $cobertura_salud_id
 * @property $tipo_afiliacion_id
 * @property $registra_beneficiarios
 * @property $observaciones
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class PersonasAfiliada extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombres_apellidos', 'cedula', 'edad', 'fecha_nacimiento', 'lugar_nacimiento_id', 'fecha_siniestro', 'lugar_siniestro_id', 'estado_civil_siniestro_id', 'estado_civil_desde', 'estado_civil_hasta', 'hijos', 'edad_hijos', 'relacion_con', 'convivencia_con', 'tiempo_convivencia', 'direccion_residencia', 'titular_trabaja', 'empresa', 'cargo', 'tiempo_laboral', 'salario', 'telefono', 'cobertura_salud_id', 'tipo_afiliacion_id', 'registra_beneficiarios', 'observaciones'];


}
