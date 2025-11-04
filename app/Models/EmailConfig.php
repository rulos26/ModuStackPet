<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'mailer',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
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
            'mail.mailer' => $this->mailer,
            'mail.host' => $this->host,
            'mail.port' => $this->port,
            'mail.username' => $this->username,
            'mail.password' => $this->password,
            'mail.encryption' => $this->encryption,
            'mail.from.address' => $this->from_address,
            'mail.from.name' => $this->from_name ?? config('app.name'),
        ];
    }

    /**
     * Verificar si la configuración está completa
     */
    public function isConfigured(): bool
    {
        return !empty($this->host) && 
               !empty($this->port) && 
               !empty($this->username) && 
               !empty($this->password) &&
               !empty($this->from_address);
    }
}
