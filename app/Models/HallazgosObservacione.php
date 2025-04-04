<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HallazgosObservacione
 *
 * @property $id
 * @property $cedula_numero
 * @property $hallazgos
 * @property $observaciones
 * @property $created_at
 * @property $updated_at
 *
 * @property Afiliado $afiliado
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class HallazgosObservacione extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cedula_numero', 'hallazgos', 'observaciones'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function afiliado()
    {
        return $this->belongsTo(\App\Models\Afiliado::class, 'cedula_numero', 'cedula_numero');
    }
    
}
