<?php

namespace App\Providers;

use App\Models\Ad;
use App\Models\CustomFootball\Chat;
use App\Models\Message;
use App\Observers\AdObserver;
use App\Observers\CustomFootball\ChatObserver;
use App\Observers\MessageObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Ad::observe(AdObserver::class);
        Message::observe(MessageObserver::class);

        Chat::observe(ChatObserver::class);
    }
}
