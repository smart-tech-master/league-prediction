<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Models\Country;

class CountryController extends Controller
{
    public function index(){

        return CountryResource::collection(Country::all());
    }
}
