<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoEmpresa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tipos_empresas';

    protected $fillable = [
        'nombre'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Obtiene las empresas que pertenecen a este tipo
     */
    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'tipo_empresa_id');
    }
}
