<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DatosBasico
 *
 * @property int $id
 * @property string $cedula_numero
 * @property string $nombre_afiliado
 * @property string $caso
 * @property string $fecha
 * @property int $estado_civil
 * @property int $amparo
 * @property int $tipo_de_convivencia
 * @property string|null $otro
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Amparo $amparo
 * @property EstadosCivile $estadosCivile
 * @property TipoDeConvivencia $tipoDeConvivencia
 */
class DatosBasico extends Model
{
    protected $table = 'datos_basicos'; // Especificar el nombre exacto de la tabla
    protected $primaryKey = 'id'; // Clave primaria

    protected $fillable = [
        'cedula_numero', 
        'nombre_afiliado', 
        'caso', 
        'fecha', 
        'estado_civil', 
        'amparo', 
        'tipo_de_convivencia', 
        'otro'
    ];

    // Cargar relaciones automáticamente
    protected $with = ['amparo', 'estadosCivile', 'tipoDeConvivencia'];

    /**
     * Relación con Amparo
     */
    public function amparo()
    {
        return $this->belongsTo(\App\Models\Amparo::class, 'amparos', 'id');
    }

    /**
     * Relación con EstadosCivile
     */
    public function estadosCivile()
    {
        return $this->belongsTo(\App\Models\EstadosCivile::class, 'estado_civil', 'id');
    }

    /**
     * Relación con TipoDeConvivencia
     */
    public function tipoDeConvivencia()
    {
        return $this->belongsTo(\App\Models\TipoDeConvivencia::class, 'tipo_de_convivencia', 'id');
    }
}
