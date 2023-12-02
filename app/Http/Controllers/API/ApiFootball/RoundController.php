<?php

namespace App\Http\Controllers\API\ApiFootball;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiFootball\Rounds\IndexResource;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Round;
use App\Models\ApiFootball\Season;

class RoundController extends Controller
{
    public function index(League $league, Season $season){
        return IndexResource::collection(Round::whereBelongsTo($league)->whereBelongsTo($season)->get());
    }
}
