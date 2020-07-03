<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportLastDay extends Notification
{
    use Queueable;
    private $datetime;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($datetime)
    {
        $this->datetime = $datetime;
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
                    ->subject('INFORMACION PARA ELABORACION DE PLANILLA')
                    ->greeting('Buen día Estimados.')
                    ->line('Por este medio se les recuerda que la entrega de la información relacionada con la Planilla:')
                    ->line('Acciones de Personal, Horas Extras, Bajas, Altas, Permisos, descuentos de facturas, Nuevos Ingresos etc., Se recibirá dicha información hasta el día:')
                    ->line($this->datetime)
                    ->action('Ingresar', url('/home'))
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
