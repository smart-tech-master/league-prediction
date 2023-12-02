<?php

namespace App\Notifications;

use App\Http\Resources\AdResource;
use App\Models\Ad;
use App\Notifications\Channels\FCMChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdCreated extends Notification
{
    use Queueable;

    public $ad;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Ad $ad)
    {
        $this->ad = $ad;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [FCMChannel::class];
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
        return AdResource::make($this->ad)->resolve();
    }
}
