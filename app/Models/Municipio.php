<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $perPage = 20;

    protected $fillable = ['nombre', 'estado', 'id_departamento'];

    /**
     * Un municipio tiene muchos afiliados.
     */
    public function afiliados()
    {
        return $this->hasMany(\App\Models\Afiliado::class, 'municipio', 'id');
    }

    /**
     * Un municipio pertenece a un departamento.
     */
    public function departamento()
    {
        return $this->belongsTo(\App\Models\Departamento::class, 'id_departamento', 'id');
    }
}
