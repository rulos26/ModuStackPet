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

    /**
     * Actualizar el archivo .env con esta configuración
     * 
     * @return bool True si se actualizó correctamente, False en caso contrario
     */
    public function updateEnvFile(): bool
    {
        try {
            $envPath = base_path('.env');

            if (!file_exists($envPath)) {
                \Log::warning('Archivo .env no encontrado', ['path' => $envPath]);
                return false;
            }

            // Leer el contenido del archivo .env
            $envContent = file_get_contents($envPath);

            if ($envContent === false) {
                \Log::error('No se pudo leer el archivo .env', ['path' => $envPath]);
                return false;
            }

            // Actualizar las variables de base de datos
            $envContent = $this->updateEnvVariable($envContent, 'DB_CONNECTION', $this->connection);
            $envContent = $this->updateEnvVariable($envContent, 'DB_HOST', $this->host);
            $envContent = $this->updateEnvVariable($envContent, 'DB_PORT', (string)$this->port);
            $envContent = $this->updateEnvVariable($envContent, 'DB_DATABASE', $this->database);
            $envContent = $this->updateEnvVariable($envContent, 'DB_USERNAME', $this->username);
            $envContent = $this->updateEnvVariable($envContent, 'DB_PASSWORD', $this->password);

            // Crear backup del .env antes de actualizar
            $backupPath = base_path('.env.backup.' . date('Y-m-d_H-i-s'));
            if (file_put_contents($backupPath, file_get_contents($envPath)) === false) {
                \Log::warning('No se pudo crear backup del .env', ['backup_path' => $backupPath]);
            }

            // Escribir el contenido actualizado
            if (file_put_contents($envPath, $envContent) === false) {
                \Log::error('No se pudo escribir el archivo .env', ['path' => $envPath]);
                return false;
            }

            \Log::info('Archivo .env actualizado desde DatabaseConfig', [
                'config_id' => $this->id,
                'host' => $this->host,
                'database' => $this->database,
                'backup_path' => $backupPath,
            ]);

            return true;

        } catch (\Exception $e) {
            \Log::error('Error al actualizar archivo .env', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'config_id' => $this->id,
            ]);
            return false;
        }
    }

    /**
     * Actualizar una variable específica en el contenido del .env
     * 
     * @param string $content Contenido del archivo .env
     * @param string $key Nombre de la variable
     * @param string $value Valor nuevo
     * @return string Contenido actualizado
     */
    protected function updateEnvVariable(string $content, string $key, string $value): string
    {
        // Escapar valores que contengan espacios o caracteres especiales
        $escapedValue = $this->escapeEnvValue($value);

        // Patrón para buscar la variable (puede estar comentada o no)
        $pattern = '/^' . preg_quote($key, '/') . '=.*$/m';

        // Nueva línea con el valor actualizado
        $replacement = $key . '=' . $escapedValue;

        // Si la variable existe, reemplazarla
        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, $replacement, $content);
        } else {
            // Si no existe, agregarla al final
            $content .= "\n" . $replacement;
        }

        return $content;
    }

    /**
     * Escapar el valor para el archivo .env
     * Si contiene espacios o caracteres especiales, usar comillas
     * 
     * @param string $value
     * @return string
     */
    protected function escapeEnvValue(string $value): string
    {
        // Si está vacío, retornar string vacío
        if (empty($value)) {
            return '';
        }

        // Si contiene espacios, comillas simples/dobles, o caracteres especiales, usar comillas
        if (preg_match('/[\s"\'#=]/', $value)) {
            // Escapar comillas dobles dentro del valor
            $value = str_replace('"', '\\"', $value);
            return '"' . $value . '"';
        }

        return $value;
    }
}
