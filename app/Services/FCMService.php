<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FCMService{

    protected $http;

    public function __construct($serverKey)
    {
        $this->http = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ]);
    }

    public function send($to, $notification){
        if(! is_null($to)) {
            return $this->http->post('https://fcm.googleapis.com/fcm/send', [
                'to' => $to,
                'notification' => $notification,
                'data' => $notification,
            ]);
        }
    }
}
