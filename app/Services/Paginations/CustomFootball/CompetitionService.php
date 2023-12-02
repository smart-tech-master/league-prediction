<?php

namespace App\Services\Paginations\CustomFootball;

use App\Http\Resources\Users\SimpleResource;
use App\Models\CustomFootball\Competition;
use App\Models\PostMatchPositioning;
use App\Models\User;
use App\Services\RankService;
use Illuminate\Support\Facades\DB;

class CompetitionService
{

    public static function getLeagueCompetitorsByCompetitionId(Competition $competition)
    {
        // if ($competition->category == 'league' && $competition->competitors()->count()) {
        if ($competition->competitors()->count()) {
            $league = $competition->league;
            $season = $competition->season;

            $competitors = PostMatchPositioning::whereRaw('user_id in (' . $competition->competitors()->select('id')->get()->pluck('id')->implode(',') . ')')
                ->whereBelongsTo($league)->whereBelongsTo($season)->orderByDesc('total_points')->orderBy('current_world_position')->get();

            foreach ($competitors as $key => $competitor) {
                $user = User::where("id", $competitor->user_id)->first();
                if($user){
                    $competitor->total_points_on_last_round = RankService::getPointsFromLastRoundInPredictionTable($user, $league, $season);
                    $competitor->total_points = RankService::points($user, $league, $season);
                    $competitor->update();
                }
            }
            
            $competitors = PostMatchPositioning::whereRaw('user_id in (' . $competition->competitors()->select('id')->get()->pluck('id')->implode(',') . ')')
            ->whereBelongsTo($league)->whereBelongsTo($season)->orderByDesc('total_points')->orderBy('current_world_position')
            ->paginate(200);

            foreach ($competitors as $key => $competitor) {
                $position = $key + 1;
                $competitors[$key]->current_world_position = $position;
            }

            return $competitors;
            // return PostMatchPositioning::whereRaw('user_id in (' . $competition->competitors()->select('id')->get()->pluck('id')->implode(',') . ')')
            //     ->whereBelongsTo($league)->whereBelongsTo($season)->orderByDesc('total_points')->orderBy('current_world_position')
            //     ->paginate(50);

        //    $competitors = $competition->competitors()->get()
        //        ->map(function ($user) use ($league, $season) {
        //            return [
        //                'user' => SimpleResource::make($user)->resolve(),
        //                'points' => RankService::points($user, $league, $season)
        //            ];
        //        })
        //        ->sortByDesc('points')
        //        ->values()
        //        ->map(function ($item, $key) {
        //            return array_merge($item, ['position' => $key + 1]);
        //        });

        //    $filtered = $competitors;

        //    return $competitors->map(function ($item) use ($league, $season, $filtered) {
        //        $user = User::findOrFail($item['user']['id']);
        //        return [
        //            'user' => $item['user'],
        //            'points' => $item['points'],
        //            'position' => $item['position'],
        //            'most_recent_position' => RankService::getMostRecentPosition($filtered, $user, $league, $season, 'world', null),
        //            'points_from_last_round' => RankService::getPointsFromLastRound($user, $league, $season),
        //        ];
        //    });
        }
        return null;
    }

}
