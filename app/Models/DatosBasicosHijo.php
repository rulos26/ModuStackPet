<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DatosBasicosHijo
 *
 * @property $id
 * @property $cedula_numero
 * @property $numero_hijos
 * @property $nombre
 * @property $tipo_documento
 * @property $documento
 * @property $edad
 * @property $created_at
 * @property $updated_at
 *
 * @property Afiliado $afiliado
 * @property TipoDocumento $tipoDocumento
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class DatosBasicosHijo extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cedula_numero', 'numero_hijos', 'nombre', 'tipo_documento', 'documento', 'edad'];


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
    public function tipoDocumento()
    {
        return $this->belongsTo(\App\Models\TipoDocumento::class, 'tipo_documento', 'id');
    }
    
}
