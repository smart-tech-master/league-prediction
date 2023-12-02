<?php

namespace App\Http\Controllers\API\ApiFootball;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiFootball\FixtureResource;
use App\Models\ApiFootball\Fixture;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Round;
use App\Models\ApiFootball\Season;

use App\Models\LeagueUser;
use Illuminate\Http\Request;
use App\Http\Resources\ApiFootball\LeagueResource;

class FixtureController extends Controller
{
    public function index(League $league, Season $season, Round $round){
        return FixtureResource::collection(Fixture::whereBelongsTo($league)->whereBelongsTo(Season::first())->whereBelongsTo($round)->orderBy('timestamp')->paginate(10));
        // return FixtureResource::collection(Fixture::whereBelongsTo($league)->whereBelongsTo(Season::first())->whereBelongsTo($round)->get());
    }

    public function upcomingMatches(Request $request) {
        $userLeagues = LeagueUser::where('user_id', $request->user()->id)->get();

        foreach ($userLeagues as $key => $userLeague) {
            $leagues[] = League::where('id', $userLeague->league_id)->first();
        }
        
        return ['leagues' => LeagueResource::collection($leagues)];
    }
}
