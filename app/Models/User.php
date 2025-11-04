<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use Notifiable;
    use HasRoles;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'password',
        'tipo_documento', 'cedula', 'avatar',
        'telefono', 'whatsapp', 'activo', 'fecha_nacimiento',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
            'fecha_nacimiento' => 'date',
        ];
    }

    /**
     * Relación: Un usuario puede tener un perfil de cliente
     */
    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }

    /**
     * Relación: Un usuario puede tener un perfil de paseador
     */
    public function paseador()
    {
        return $this->hasOne(Paseador::class);
    }

    /**
     * Relación: Un usuario puede tener múltiples cuentas sociales
     */
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * Relación: Un usuario puede tener múltiples mascotas
     */
    public function mascotas()
    {
        return $this->hasMany(Mascota::class);
    }

    /**
     * Verificar si el usuario es un cliente
     */
    public function isCliente(): bool
    {
        return $this->hasRole('Cliente');
    }

    /**
     * Verificar si el usuario es un paseador
     */
    public function isPaseador(): bool
    {
        return $this->hasRole('Paseador');
    }

    /**
     * Enviar la notificación de verificación de email personalizada en español
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmailNotification());
    }
}
