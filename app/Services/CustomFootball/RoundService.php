<?php

namespace App\Services\CustomFootball;

use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Round;
use App\Models\ApiFootball\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoundService
{
    public function index(Request $request, League $league, Season $season)
    {
        $rounds = Round::whereBelongsTo($league)->whereBelongsTo($season)->get();
        //return $rounds;
        $take = $rounds->count() - $this->remainingRounds($request->type, $request->participants);

        return $rounds->take($take < 0 ? 0 : ($take == 0 ? 1 : $take));
    }

    public function remainingRounds($type, $participants)
    {
        $log = log($participants, 2);
        switch (Str::camel($type)) {
            case 'homeAndAway':
                return ($log * 2) - 1;
            case 'oneMatch':
                return $log;
        }
    }

    public static function getCurrentRound(League $league, Season $season)
    {
        $currentRound = Round::whereBelongsTo($league)->whereBelongsTo($season)->where('current', true)->first();
        if($currentRound){
            return $currentRound;
        }

        return self::getLastRound($league, $season);
    }

    public static function getLastRound(League $league, Season $season)
    {
        return Round::whereBelongsTo($league)->whereBelongsTo($season)->orderBy('sl', 'desc')->first();
    }
}
