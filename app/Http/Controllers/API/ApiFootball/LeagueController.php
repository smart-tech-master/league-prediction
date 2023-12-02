<?php

namespace App\Http\Controllers\API\ApiFootball;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiFootball\LeagueResource;
use App\Models\ApiFootball\League;

class LeagueController extends Controller
{
    public function index(){
        return LeagueResource::collection(League::all());
    }
}
