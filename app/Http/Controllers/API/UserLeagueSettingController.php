<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Models\Ad;
use App\Models\User;
use App\Models\LeagueUser;
use App\Http\Resources\AdResource;
use App\Http\Resources\ApiFootball\LeagueResource;
use App\Jobs\PostMatchPositioning\ProcessSubscription;

class UserLeagueSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return [
            'leagues' => LeagueResource::collection(League::all())
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        LeagueUser::where('user_id', auth()->user()->id)->delete();
        foreach ($request->is_subscribed as $key => $league_id) {
            LeagueUser::create([
                'user_id' => auth()->user()->id,
                'league_id' => $league_id,
                'season_id' => Season::first()->id
            ]);

            ProcessSubscription::dispatch(User::find(auth()->user()->id), League::find($league_id), Season::first());
        }

        return ['leagues' => LeagueResource::collection(League::all())];
    }

    public function show($league_id)
    {
        return [
            'league' => League::find($league_id) ? LeagueResource::make(League::find($league_id)) : null
        ];
    }
}
