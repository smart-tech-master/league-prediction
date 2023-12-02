<?php

namespace App\Http\Controllers\API\Paginations\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ApiFootball\Season;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Round;
use App\Services\Paginations\Ranks\LeagueService;
use App\Services\RankService;
use App\Services\ApiFootball\RoundService;
use App\Http\Resources\Paginations\Users\RoundResource;

class RoundController extends Controller
{
    public function index(User $user, League $league){
        // return IndexResource::collection(Round::whereBelongsTo($league)->whereBelongsTo($season)->get());
        // return LeagueService::index($user, $season);
        // $rounds = Round::whereBelongsTo($league)->where('round_id', '<=', RoundService::getCurrentRound($league, $season))->orderByDesc("id")->get();
        $season = Season::first();
        $currentRound = RoundService::getCurrentRound($league, $season);
        $rounds = Round::whereBelongsTo($league)->where('id', '<=', $currentRound->id)->orderBy("id")->get();
        foreach ($rounds as $key => $round) {
            $round->user = $user;
            $round->league = $league;
        }
        // $points = RankService::pointsByRound($user, $league, $rounds[0]);
        return RoundResource::collection($rounds);
    }
}
