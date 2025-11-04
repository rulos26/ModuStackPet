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

            // Actualizar las variables de correo
            $envContent = $this->updateEnvVariable($envContent, 'MAIL_MAILER', $this->mailer);
            $envContent = $this->updateEnvVariable($envContent, 'MAIL_HOST', $this->host);
            $envContent = $this->updateEnvVariable($envContent, 'MAIL_PORT', (string)$this->port);
            $envContent = $this->updateEnvVariable($envContent, 'MAIL_USERNAME', $this->username);
            $envContent = $this->updateEnvVariable($envContent, 'MAIL_PASSWORD', $this->password);
            $envContent = $this->updateEnvVariable($envContent, 'MAIL_ENCRYPTION', $this->encryption);
            $envContent = $this->updateEnvVariable($envContent, 'MAIL_FROM_ADDRESS', $this->from_address);
            
            // MAIL_FROM_NAME puede ser null, usar el valor si existe o el nombre de la app
            $fromName = $this->from_name ?? config('app.name');
            $envContent = $this->updateEnvVariable($envContent, 'MAIL_FROM_NAME', $fromName);

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

            \Log::info('Archivo .env actualizado desde EmailConfig', [
                'config_id' => $this->id,
                'host' => $this->host,
                'from_address' => $this->from_address,
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

        // Patrón para buscar la variable (puede estar comentada o no, con o sin espacios)
        // Busca: # MAIL_HOST=xxx  o  MAIL_HOST=xxx  o  MAIL_HOST = xxx
        $pattern = '/^(\s*#?\s*)' . preg_quote($key, '/') . '(\s*=\s*)(.*)$/m';

        // Nueva línea con el valor actualizado (sin comentario)
        $replacement = $key . '=' . $escapedValue;

        // Si la variable existe (comentada o no), reemplazarla
        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, $replacement, $content);
        } else {
            // Si no existe, agregarla al final (después de la última línea)
            // Asegurarse de que termina con nueva línea
            $content = rtrim($content) . "\n" . $replacement;
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
