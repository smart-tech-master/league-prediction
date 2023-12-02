<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AvailabilityRequest;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index(AvailabilityRequest $request, $col){
        return response()->json(['message' => 'Available']);
    }
}
