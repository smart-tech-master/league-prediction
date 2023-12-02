<?php

namespace App\Http\Controllers\API\Paginations\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ApiFootball\Season;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Round;
use App\Models\ApiFootball\Fixture;
use App\Http\Resources\Paginations\Users\FixtureResource;
use Illuminate\Http\Request;

class FixtureController extends Controller
{
    public function index(Request $request, User $user, League $league, Round $round){
        $fixtures = Fixture::whereBelongsTo($league)->whereBelongsTo(Season::first())->whereBelongsTo($round)->orderBy('timestamp')->get();

        foreach ($fixtures as $key => $fixture) {
            $fixture->user = $user;
        }

        return FixtureResource::collection($fixtures);
    }
}
