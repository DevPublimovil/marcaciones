<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovedAction extends Notification
{
    use Queueable;

    private $type;
    private $url;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
        $this->url = '/actions';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Acción de personal Aprobada')
                    ->greeting('¡Hola '. $notifiable->name .'!')
                    ->line('Tu acción de personal ha sido aprobada por ' . $this->type .'.')
                    ->line('Puedes ingresar a la aplicación para revisar su estado')
                    ->action('Ingresar', url($this->url))
                    ->line('¡Gracias por usar nuestra Aplicación!')
                    ->salutation('¡Saludos!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
