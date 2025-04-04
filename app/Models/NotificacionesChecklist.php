<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NotificacionesChecklist
 *
 * @property $id
 * @property $cedula
 * @property $nueva
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class NotificacionesChecklist extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cedula', 'nueva'];

    // Método para contar las notificaciones nuevas
    public static function contarNuevas()
    {
        return self::where('nueva', true)->exists() ? 1 : 0;
    }


}
