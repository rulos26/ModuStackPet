<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $perPage = 20;

    protected $fillable = ['nombre'];

    /**
     * Un departamento tiene muchos afiliados.
     */
    public function afiliados()
    {
        return $this->hasMany(\App\Models\Afiliado::class, 'departamento', 'id');
    }

    /**
     * Un departamento tiene muchos municipios.
     */
    public function municipios()
    {
        return $this->hasMany(\App\Models\Municipio::class, 'id_departamento', 'id');
    }
}
