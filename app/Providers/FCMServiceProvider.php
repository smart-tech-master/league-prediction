<?php

namespace App\Providers;

use App\Services\FCMService;
use Illuminate\Support\ServiceProvider;

class FCMServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('fcm', function ($app) {
            return new FCMService(env('FCM_SERVER_KEY'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

}
