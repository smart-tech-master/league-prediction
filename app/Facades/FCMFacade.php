<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class FCMFacade extends Facade {
    protected static function getFacadeAccessor() { return 'fcm'; }
}
