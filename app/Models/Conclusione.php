<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Conclusione
 *
 * @property $id
 * @property $cedula_numero
 * @property $documentos
 * @property $nexos
 * @property $muerte_origen
 * @property $reclamante
 * @property $nombre_reclamante
 * @property $afiliado_deja_descendiente
 * @property $descendientes_relacion
 * @property $descendientes_afiliado
 * @property $datos_hijo
 * @property $presenta_condicion_discapacidad
 * @property $observaciones
 * @property $created_at
 * @property $updated_at
 *
 * @property Afiliado $afiliado
 * @property DatosBasicosHijo $datosBasicosHijo
 * @property MuertesOrigen $muertesOrigen
 * @property Reclamante $reclamante
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Conclusione extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cedula_numero', 'documentos', 'nexos', 'muerte_origen', 'reclamante', 'nombre_reclamante', 'afiliado_deja_descendiente', 'descendientes_relacion', 'descendientes_afiliado', 'datos_hijo', 'presenta_condicion_discapacidad', 'observaciones'];


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
    public function datosBasicosHijo()
    {
        return $this->belongsTo(\App\Models\DatosBasicosHijo::class, 'datos_hijo', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function muertesOrigen()
    {
        return $this->belongsTo(\App\Models\MuertesOrigen::class, 'muerte_origen', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reclamante()
    {
        return $this->belongsTo(\App\Models\Reclamante::class, 'reclamantes', 'id');
    }
    
}
