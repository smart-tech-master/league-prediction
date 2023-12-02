<?php

namespace App\Http\Controllers;

use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Round;
use App\Models\ApiFootball\Season;
use Illuminate\Http\Request;

class ApiFootballController extends Controller
{
    public function getLeagues(){
        return view('api-football.leagues')
            ->withLeagues(League::all())
            ->withSeason(Season::first());
    }


    public function getFixtures(Request $request, League $league, Season $season){
        return view('api-football.fixtures')->withRounds(Round::whereBelongsTo($league)->whereBelongsTo($season)->get());
    }
}
