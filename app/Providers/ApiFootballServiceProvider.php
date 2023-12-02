<?php

namespace App\Providers;

use App\Services\ApiFootball\ApiFootballService;
use Illuminate\Support\ServiceProvider;

class ApiFootballServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('api-football',function ($app) {
            return new ApiFootballService(env('X_RAPIDAPI_KEY'), env('X_RAPIDAPI_HOST'));
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
