<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificacionSimple extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // o solo ['database'] si no quieres email
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject('Bienvenido al sistema')
        ->greeting('¡Hola ' . $notifiable->name . '!')
        ->line('Gracias por iniciar sesión. Disfruta tu experiencia.')
        ->action('Ir al Dashboard', url('/dashboard'))
        ->line('¡Gracias por usar nuestra aplicación!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Bienvenido al sistema',
            'message' => 'Gracias por iniciar sesión. Disfruta tu experiencia.',
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'titulo' => '¡Nueva notificación!',
            'mensaje' => 'Tienes una nueva notificación desde el sistema.',
            'url' => url('/dashboard')
        ];
    }
}
