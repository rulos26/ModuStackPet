<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CredencialesPaseadorNotification extends Notification
{
    use Queueable;

    public $password;
    public $email;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $password, string $email)
    {
        $this->password = $password;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Credenciales de Acceso - ModuStackPet')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Has sido registrado como Paseador en nuestra plataforma ModuStackPet.')
            ->line('A continuación encontrarás tus credenciales de acceso:')
            ->line('**Email:** ' . $this->email)
            ->line('**Contraseña:** ' . $this->password)
            ->line('**Importante:** Por seguridad, te recomendamos cambiar tu contraseña después del primer acceso.')
            ->action('Iniciar Sesión', url('/login'))
            ->line('Si tienes alguna pregunta, no dudes en contactarnos.')
            ->salutation('Saludos, El equipo de ModuStackPet');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'password_sent' => true,
            'email' => $this->email,
        ];
    }
}
