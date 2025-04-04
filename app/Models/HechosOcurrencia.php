<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HechosOcurrencia
 *
 * @property $id
 * @property $cedula_numero
 * @property $dia
 * @property $horas
 * @property $lugar
 * @property $motivo_muerte
 * @property $otros
 * @property $deceso_se_origna
 * @property $donde_fallese
 * @property $funeraria
 * @property $fallecido
 * @property $cuerpo_fue
 * @property $servicos_funerarios
 * @property $created_at
 * @property $updated_at
 *
 * @property Afiliado $afiliado
 * @property MotivosMuerte $motivosMuerte
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class HechosOcurrencia extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cedula_numero', 'dia', 'horas', 'lugar', 'motivo_muerte', 'otros', 'deceso_se_origna', 'donde_fallese', 'funeraria', 'fallecido', 'cuerpo_fue', 'servicos_funerarios'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function afiliado()
    {
        return $this->belongsTo(\App\Models\Afiliado::class, 'cedula_numero', 'cedula_numero');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function motivosMuerte()
    {
        return $this->belongsTo(\App\Models\MotivosMuerte::class, 'motivo_muerte', 'id');
    }
    
}
