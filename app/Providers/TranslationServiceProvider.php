<?php

namespace App\Providers;

use App\Services\L10n\TranslationLoader;
use Illuminate\Translation\TranslationServiceProvider as ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new TranslationLoader($app['files'], $app['path.lang']);
        });
    }
}
