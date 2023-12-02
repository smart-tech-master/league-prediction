<?php

namespace App\Providers;

use App\Services\FileUploadService;
use Illuminate\Support\ServiceProvider;

class FileUploadServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('file-upload',function ($app) {
            return new FileUploadService();
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
