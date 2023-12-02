<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ApiFootballFacade extends Facade {
    protected static function getFacadeAccessor() { return 'api-football'; }
}
