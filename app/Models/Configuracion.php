<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Configuracion extends Model
{
    /**
     * Nombre de la tabla (Laravel busca 'configuracions' por defecto)
     */
    protected $table = 'configuraciones';

    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
        'tipo',
        'categoria',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Obtener el valor de una configuración específica
     * Con cache para mejorar el rendimiento
     */
    public static function obtenerValor($clave, $default = null)
    {
        return Cache::remember("config_{$clave}", 3600, function () use ($clave, $default) {
            $config = self::where('clave', $clave)
                ->where('activo', true)
                ->first();

            return $config ? $config->valor : $default;
        });
    }

    /**
     * Obtener el timeout de sesión en segundos
     */
    public static function getSessionTimeout()
    {
        $timeout = self::obtenerValor('session_timeout', 1800);
        return (int) $timeout;
    }

    /**
     * Actualizar una configuración y limpiar cache
     */
    public function actualizarValor($valor)
    {
        $this->update(['valor' => $valor]);
        Cache::forget("config_{$this->clave}");
        return $this;
    }
}
