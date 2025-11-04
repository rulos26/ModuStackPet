<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'connection',
        'host',
        'port',
        'database',
        'username',
        'password',
        'execute_seeders',
        'is_active',
        'last_backup_result',
        'last_backup_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'execute_seeders' => 'boolean',
        'port' => 'integer',
        'last_backup_at' => 'datetime',
        'last_backup_result' => 'array',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Relación con logs de backup
     */
    public function backupLogs()
    {
        return $this->hasMany(BackupLog::class);
    }

    /**
     * Obtener configuración como array para conexión
     */
    public function toConfigArray(): array
    {
        return [
            'driver' => $this->connection,
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

    /**
     * Verificar que la BD destino no sea la de producción
     */
    public function isProductionDatabase(): bool
    {
        $defaultConnection = config('database.default');
        $productionDb = config("database.connections.{$defaultConnection}.database", null);
        
        if ($productionDb === null) {
            return false; // Si no se puede obtener la BD de producción, permitir el backup
        }
        
        return $this->database === $productionDb;
    }
}
