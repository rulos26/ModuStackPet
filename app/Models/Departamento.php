<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Departamento
 *
 * Este modelo representa los departamentos en el sistema.
 *
 * @property int $id_departamento ID único del departamento
 * @property string $nombre Nombre del departamento
 * @property int $estado Estado del departamento (1: activo, 0: inactivo)
 * @property \Carbon\Carbon $created_at Fecha de creación
 * @property \Carbon\Carbon $updated_at Fecha de última actualización
 * @property \Carbon\Carbon|null $deleted_at Fecha de eliminación (soft delete)
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento ordenarPorNombre()
 */
class Departamento extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla asociada al modelo
     *
     * @var string
     */
    protected $table = 'departamentos';

    /**
     * Nombre de la clave primaria
     *
     * @var string
     */
    protected $primaryKey = 'id_departamento';

    /**
     * Número de registros por página en la paginación
     *
     * @var int
     */
    protected $perPage = 20;

    /**
     * Atributos que se pueden asignar masivamente
     *
     * @var array<string>
     */
    protected $fillable = ['nombre', 'estado'];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     *
     * @var array<string, string>
     */
    protected $casts = [
        'estado' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Reglas de validación para el departamento
     *
     * @var array<string, string>
     */
    public static $rules = [
        'nombre' => 'required|string|max:100|unique:departamentos,nombre',
        'estado' => 'required|boolean'
    ];

    /**
     * Scope para ordenar departamentos por nombre
     */
    public function scopeOrdenarPorNombre($query)
    {
        return $query->orderBy('nombre', 'asc');
    }

    /**
     * Obtener el nombre del departamento en mayúsculas
     */
    public function getNombreMayusculasAttribute(): string
    {
        return strtoupper($this->nombre);
    }

    /**
     * Relación con las ciudades
     */
    public function ciudades()
    {
        return $this->hasMany(Ciudade::class, 'departamento_id', 'id_departamento');
    }
}
