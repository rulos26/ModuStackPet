<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autorizacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'foto_perfil_path',
        'foto_cedula_path',
        'foto_firma_path',
        'direccion',
        'barrio',
        'localidad',
        'telefono_fijo',
        'celular',
        'correo_electronico',
    ];
}
