<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AfiliadosCoberturasSalud
 *
 * @property $id
 * @property $cedula_numero
 * @property $cobertura_salud
 * @property $tipo_afiliacion
 * @property $regimen
 * @property $desde
 * @property $registra_beneficiarios
 * @property $quien_reclama_prestaciones_sociales
 * @property $created_at
 * @property $updated_at
 *
 * @property Afiliado $afiliado
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class AfiliadosCoberturasSalud extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cedula_numero', 'cobertura_salud', 'tipo_afiliacion', 'regimen', 'desde', 'registra_beneficiarios', 'quien_reclama_prestaciones_sociales'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function afiliado()
    {
        return $this->belongsTo(\App\Models\Afiliado::class, 'cedula_numero', 'cedula_numero');
    }
    
}
