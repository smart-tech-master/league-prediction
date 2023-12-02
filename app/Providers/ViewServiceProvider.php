<?php

namespace App\Providers;

use App\View\Composers\CountryComposer;
use App\View\Composers\PageComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.super-admin', PageComposer::class);
        View::composer('dashboard.*', CountryComposer::class);
    }
}
