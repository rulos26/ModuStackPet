<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ReclamantesAfiliado
 *
 * @property $id
 * @property $cedula_numero
 * @property $reclamante
 * @property $nombre
 * @property $tipo_documento
 * @property $cedula_reclamante
 * @property $fecha_nacimiento
 * @property $ciudad_nacimiento
 * @property $departamento_nacimiento
 * @property $edad
 * @property $fecha_expedicion
 * @property $ciudad_expedicion
 * @property $departamento_expedicion
 * @property $estado_civil
 * @property $desde_convivencia
 * @property $hasta_convivencia
 * @property $compartieron_techo_mesa_lecho
 * @property $afiliado_relacion_quedaron_hijos
 * @property $datos_basicos_hijo
 * @property $direccion_siniestro
 * @property $direccion_actual
 * @property $barrio
 * @property $ciudad
 * @property $vivienda
 * @property $canon_arrendamiento
 * @property $tiempo_residencia
 * @property $movil
 * @property $activa_laboralmente_siniestro
 * @property $trabajaba_empresa
 * @property $ocupacion
 * @property $salario
 * @property $tiempo_trabajo
 * @property $coberturas_salud
 * @property $tipos_afiliaciones
 * @property $regimen
 * @property $estado_afiliacion
 * @property $registra_beneficiarios_eps
 * @property $created_at
 * @property $updated_at
 *
 * @property Afiliado $afiliado
 * @property Municipio $municipio
 * @property Municipio $municipio
 * @property Municipio $municipio
 * @property CoberturasSalud $coberturasSalud
 * @property DatosBasicosHijo $datosBasicosHijo
 * @property Departamento $departamento
 * @property Departamento $departamento
 * @property EstadosCivile $estadosCivile
 * @property Reclamante $reclamante
 * @property TiposAfiliacione $tiposAfiliacione
 * @property TipoDocumento $tipoDocumento
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ReclamantesAfiliado extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cedula_numero', 'reclamante', 'nombre', 'tipo_documento', 'cedula_reclamante', 'fecha_nacimiento', 'ciudad_nacimiento', 'departamento_nacimiento', 'edad', 'fecha_expedicion', 'ciudad_expedicion', 'departamento_expedicion', 'estado_civil', 'desde_convivencia', 'hasta_convivencia', 'compartieron_techo_mesa_lecho', 'afiliado_relacion_quedaron_hijos', 'datos_basicos_hijo', 'direccion_siniestro', 'direccion_actual', 'barrio', 'ciudad', 'vivienda', 'canon_arrendamiento', 'tiempo_residencia', 'movil', 'activa_laboralmente_siniestro', 'trabajaba_empresa', 'ocupacion', 'salario', 'tiempo_trabajo', 'coberturas_salud', 'tipos_afiliaciones', 'regimen', 'estado_afiliacion', 'registra_beneficiarios_eps'];


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
    public function municipio_expe()
    {
        return $this->belongsTo(\App\Models\Municipio::class, 'nombre', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ciudad()
    {
        return $this->belongsTo(\App\Models\Municipio::class, 'nombre', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function municipio()
    {
        return $this->belongsTo(\App\Models\Municipio::class, 'nombre', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coberturasSalud()
    {
        return $this->belongsTo(\App\Models\CoberturasSalud::class, 'coberturas_salud', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function datosBasicosHijo()
    {
        return $this->belongsTo(\App\Models\DatosBasicosHijo::class, 'datos_basicos_hijo', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departamento()
    {
        return $this->belongsTo(\App\Models\Departamento::class, 'nombre', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departamento_nacimiento()
    {
        return $this->belongsTo(\App\Models\Departamento::class, 'nombre', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estadosCivile()
    {
        return $this->belongsTo(\App\Models\EstadosCivile::class, 'estado_civil', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reclamante()
    {
        return $this->belongsTo(\App\Models\Reclamante::class, 'reclamantes', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tiposAfiliacione()
    {
        return $this->belongsTo(\App\Models\TiposAfiliacione::class, 'tipos_afiliaciones', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipoDocumento()
    {
        return $this->belongsTo(\App\Models\TipoDocumento::class, 'tipo_documento', 'id');
    }
    
}
