<?php

namespace App\Notifications;

use App\Models\BackupLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BackupCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected BackupLog $backupLog;

    /**
     * Create a new notification instance.
     */
    public function __construct(BackupLog $backupLog)
    {
        $this->backupLog = $backupLog;
    }

    /**
     * Get the notification's delivery channels.
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
        $backupConfig = $this->backupLog->backupConfig;
        $status = $this->backupLog->status;
        
        $message = (new MailMessage)
            ->subject('Copia de Seguridad de Base de Datos - ' . ucfirst($status))
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Se ha ' . ($status === 'completed' ? 'completado' : 'fallado') . ' una copia de seguridad de la base de datos de producción.');

        if ($status === 'completed') {
            $message->line('**Detalles del Backup:**')
                ->line('- **Configuración:** ' . $backupConfig->name)
                ->line('- **Base de Datos Destino:** ' . $backupConfig->database)
                ->line('- **Tablas respaldadas:** ' . $this->backupLog->tables_backed_up . ' de ' . $this->backupLog->tables_total)
                ->line('- **Registros copiados:** ' . number_format($this->backupLog->records_backed_up, 0, ',', '.'))
                ->line('- **Iniciado:** ' . $this->backupLog->started_at->format('d/m/Y H:i:s'))
                ->line('- **Completado:** ' . $this->backupLog->completed_at->format('d/m/Y H:i:s'))
                ->line('- **Duración:** ' . $this->backupLog->started_at->diffForHumans($this->backupLog->completed_at, true));
        } else {
            $message->line('**Error:** ' . $this->backupLog->error_message)
                ->line('**Configuración:** ' . $backupConfig->name)
                ->line('**Base de Datos Destino:** ' . $backupConfig->database);
        }

        $message->action('Ver Detalles', route('superadmin.backup-configs.logs', $backupConfig))
            ->line('Este es un correo automático del sistema ModuStackPet.');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'backup_log_id' => $this->backupLog->id,
            'status' => $this->backupLog->status,
            'backup_config_id' => $this->backupLog->backup_config_id,
        ];
    }
}
