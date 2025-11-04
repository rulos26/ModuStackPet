<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatabaseConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'connection',
        'host',
        'port',
        'database',
        'username',
        'password',
        'is_active',
        'last_test_result',
        'last_tested_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'port' => 'integer',
        'last_tested_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Obtener la configuración activa
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Obtener configuración como array para config()
     */
    public function toConfigArray(): array
    {
        return [
            'driver' => $this->connection, // Laravel usa 'driver' en lugar de 'connection'
            'host' => $this->host,
            'port' => $this->port,
            'database' => $this->database,
            'username' => $this->username,
            'password' => $this->password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ];
    }

    /**
     * Verificar si la configuración está completa
     */
    public function isConfigured(): bool
    {
        return !empty($this->host) && 
               !empty($this->database) && 
               !empty($this->username) && 
               !empty($this->password);
    }
}
