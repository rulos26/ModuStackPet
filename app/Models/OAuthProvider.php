<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthProvider extends Model
{
    protected $table = 'oauth_providers';

    protected $fillable = [
        'provider',
        'name',
        'client_id',
        'client_secret',
        'redirect_uri',
        'is_active',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Obtener configuración lista para Socialite
     */
    public function getConfigAttribute(): array
    {
        return [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect' => $this->redirect_uri,
        ];
    }

    /**
     * Verificar si el provider está configurado completamente
     */
    public function isConfigured(): bool
    {
        return !empty($this->client_id) && 
               !empty($this->client_secret) && 
               !empty($this->redirect_uri);
    }

    /**
     * Obtener providers activos
     */
    public static function getActiveProviders()
    {
        return static::where('is_active', true)
            ->whereNotNull('client_id')
            ->whereNotNull('client_secret')
            ->whereNotNull('redirect_uri')
            ->get();
    }

    /**
     * Relación con logs de prueba
     */
    public function testLogs()
    {
        return $this->hasMany(OAuthTestLog::class);
    }
}
