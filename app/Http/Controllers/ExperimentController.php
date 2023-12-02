<?php

namespace App\Http\Controllers;

use App\Services\ExperimentService;
use Illuminate\Http\Request;

class ExperimentController extends Controller
{
    public function index(Request $request, $action)
    {
        return (new ExperimentService())->{$action}($request);
    }
}
