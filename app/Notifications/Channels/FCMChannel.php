<?php
namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class FCMChannel {
    public function send($notifiable, Notification $notification){

        $fcm = new \FCM();

        //\Log::debug($notification->toArray($notifiable));

        $fcm::send($notifiable->device_token, $notification->toArray($notifiable));
    }
}
