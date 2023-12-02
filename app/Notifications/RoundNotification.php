<?php

namespace App\Notifications;

use App\Models\ApiFootball\Round;
use App\Notifications\Channels\FCMChannel;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RoundNotification extends Notification
{
    use Queueable;

    public $round;
    public $type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Round $round, $type)
    {
        $this->round = $round;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', FCMChannel::class];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return (new NotificationService())->toArray($this->round, $this->type);
    }
}
