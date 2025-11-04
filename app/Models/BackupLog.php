<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'backup_config_id',
        'user_id',
        'status',
        'tables_backed_up',
        'tables_total',
        'records_backed_up',
        'error_message',
        'details',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'tables_backed_up' => 'integer',
        'tables_total' => 'integer',
        'records_backed_up' => 'integer',
        'details' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Relación con configuración de backup
     */
    public function backupConfig()
    {
        return $this->belongsTo(BackupConfig::class);
    }

    /**
     * Relación con usuario que ejecutó el backup
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
