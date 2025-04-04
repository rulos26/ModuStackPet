<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Afiliado extends Model
{
    protected $perPage = 20;

    protected $fillable = ['cedula_numero', 'edad', 'fecha_nacimiento', 'departamento', 'municipio', 'fecha_expedicion'];

    /**
     * Un afiliado pertenece a un conjunto de datos básicos (relación por cédula).
     */
    public function datosBasico()
    {
        return $this->belongsTo(\App\Models\DatosBasico::class, 'cedula_numero', 'cedula_numero');
    }

    /**
     * Un afiliado pertenece a un departamento.
     */
    public function departamento()
    {
        return $this->belongsTo(\App\Models\Departamento::class, 'departamentos', 'id');
    }

    /**
     * Un afiliado pertenece a un municipio.
     */
    public function municipio()
    {
        return $this->belongsTo(\App\Models\Municipio::class, 'municipios', 'id');
    }
}
