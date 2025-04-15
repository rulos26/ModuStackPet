<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ciudad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ciudades';
    protected $primaryKey = 'id_municipio';

    protected $fillable = [
        'municipio',
        'departamento_id',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'id_departamento');
    }

    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'ciudad_id', 'id_municipio');
    }
}
