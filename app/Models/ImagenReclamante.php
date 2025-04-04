<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenReclamante extends Model
{
    use HasFactory;
    
    protected $fillable = ['cedula_numero', 'tipo_imagen', 'ruta_imagen'];
}
