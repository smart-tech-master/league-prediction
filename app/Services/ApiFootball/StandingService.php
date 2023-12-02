<?php

namespace App\Services\ApiFootball;

use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Models\ApiFootball\Standing;
use Illuminate\Support\Facades\Log;

class StandingService
{
    public function fetch()
    {
        $season = Season::first();
        foreach (League::all() as $league) {
            $apiFootball = new \ApiFootball();
            $params = [
                'season' => $season->year,
                'league' => $league->id,
            ];
            $response = $apiFootball::standings($params)->object()->response ?? [];
            foreach (collect($response[0]->league->standings ?? [])->flatten() as $item){
                $standing = Standing::whereBelongsTo($league)->whereBelongsTo($season)->whereTeamId($item->team->id)->firstOr(function () use ($league, $season, $item){
                    return Standing::forceCreate([
                        'league_id' => $league->id,
                        'season_id' => $season->id,
                        'team_id' => $item->team->id,
                        'rank' => (Standing::whereBelongsTo($league)->whereBelongsTo($season)->count() + 1),
                    ]);
                });

                $standing->rank = $item->rank;
                $standing->update();
            }
            Log::debug(print_r(collect($response[0]->league->standings)->flatten(), true));
//            foreach ($response as $standing){
//                Log::debug(print_r($standing));
//
//            }
        }
    }
}
