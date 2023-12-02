<?php

namespace App\Services\Paginations\Ranks;

use App\Http\Resources\ApiFootball\SeasonResource;
use App\Http\Resources\ApiFootball\SimpleLeagueResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\Paginations\Ranks\Leagues\ShowResource;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Models\PostMatchPositioning;
use App\Models\User;
use App\Models\Prediction;
use App\Services\RankService;
use Illuminate\Http\Request;

class LeagueService
{
    public static function index(User $user, Season $season)
    {
        // $leagues = $user->leagues()->wherePivot('season_id', $season->id)->get();
        $leagues = RankService::userPredictedLeagues($user);

        $data = [];

        // foreach ($leagues as $league) {
        //     $data[] = [
        //         'league' => SimpleLeagueResource::make($league)->resolve(),
        //         'season' => SeasonResource::make($season)->resolve(),
        //         'world' => [
        //             'position' => $user->postMatchPositionings()->whereBelongsTo($league)->whereBelongsTo($season)->first()->current_world_position ?? 1,
        //             'total' => $user->postMatchPositionings()->whereBelongsTo($league)->whereBelongsTo($season)->first()->total_world_users ?? 1,
        //         ],
        //         'continent' => [
        //             'position' => $user->postMatchPositionings()->whereBelongsTo($league)->whereBelongsTo($season)->first()->current_continent_position ?? 1,
        //             'total' => $user->postMatchPositionings()->whereBelongsTo($league)->whereBelongsTo($season)->first()->total_continent_users ?? 1,
        //             'name' => $user->country->continent
        //         ],
        //         'country' => [
        //             'position' => $user->postMatchPositionings()->whereBelongsTo($league)->whereBelongsTo($season)->first()->current_country_position ?? 1,
        //             'total' => $user->postMatchPositionings()->whereBelongsTo($league)->whereBelongsTo($season)->first()->total_country_users ?? 1,
        //             'name' => $user->country->name,
        //         ],
        //         'points' => $user->postMatchPositionings()->whereBelongsTo($league)->whereBelongsTo($season)->first()->total_points ?? 0,
        //     ];
        // }

        // foreach ($leagues as $league){
        //     $getWorldUsers = RankService::getUsers($league, $season);
        //     $getContinentUsers = $getWorldUsers->where('country.continent', $user->country->continent);
        //     $getCountryUsers = $getWorldUsers->where('country.id', $user->country->id);
 
        //     $users = [
        //         'world' => RankService::serialize($getWorldUsers),
        //         'continent' => RankService::serialize($getContinentUsers),
        //         'country' => RankService::serialize($getCountryUsers)
        //     ];
 
        //     $data[] = [
        //         'league' => SimpleLeagueResource::make($league)->resolve(),
        //         'season' => SeasonResource::make($season)->resolve(),
        //         'world' => [
        //             //'position' => $users['world']->count(),
        //             'position' => $users['world']->firstWhere('user.id', $user->id)['position'] ?? null,
        //             'total' => $users['world']->count(),
        //         ],
        //         'continent' => [
        //             //'position' => $users['continent']->count(),
        //             'position' => $users['continent']->firstWhere('user.id', $user->id)['position'] ?? null,
        //             'total' => $users['continent']->count(),
        //             'name' => $user->country->continent
        //         ],
        //         'country' => [
        //             //'position' => $users['country']->count(),
        //             'position' => $users['country']->firstWhere('user.id', $user->id)['position'] ?? null,
        //             'total' => $users['country']->count(),
        //             'name' => $user->country->name,
        //         ],
        //         'points' => $getWorldUsers->firstWhere('user.id', $user->id)['points'] ?? null,
        //     ];
        // }

        foreach ($leagues as $league){
            $getWorldUsers = RankService::getLeagueUsers($league, $season);
            // $getContinentUsers = $getLeagueUsers->where('country.continent', $user->country->continent);
            $getCountryUsers = RankService::getLeagueUsers($league, $season, 'country', $user->country->id);

            $users = [
                'world' => $getWorldUsers,
                // 'continent' => $getContinentUsers,
                'country' => $getCountryUsers
            ];

            $data[] = [
                'league' => SimpleLeagueResource::make($league)->resolve(),
                'season' => SeasonResource::make($season)->resolve(),
                'world' => [
                    'position' => $users['world']->firstWhere('user.id', $user->id)['position'] ?? null,
                    'total' => $users['world']->count(),
                ],
                // 'continent' => [
                //     'position' => $users['continent']->firstWhere('user.id', $user->id)['position'] ?? null,
                //     'total' => $users['continent']->count(),
                //     'name' => $user->country->continent
                // ],
                'country' => [
                    'id' => $user->country->id,
                    'name' => $user->country->name,
                    'position' => $users['country']->firstWhere('user.id', $user->id)['position'] ?? null,
                    'total' => $users['country']->count(),
                ],
                'points' => RankService::points($user, $league, $season) ?? null,
            ];
        }
        return $data;
    }

    public static function show(Request $request, League $league, Season $season)
    {
        // $postMatchPositionings = PostMatchPositioning::whereBelongsTo($league)->whereBelongsTo($season);
        // switch ($request->tops) {
        //     case 'world':
        //         $postMatchPositionings = PostMatchPositioning::whereBelongsTo($league)->whereBelongsTo($season)->orderBy('current_world_position');
        //         break;

        //     case 'continent':
        //         $postMatchPositionings = PostMatchPositioning::whereBelongsTo($league)->whereBelongsTo($season)->whereHas('user.country', function ($query) use ($request) {
        //             $query->where('continent', $request->user()->country->continent);
        //         })->orderBy('current_continent_position');

        //         break;
        //     case 'country':
        //         $postMatchPositionings = PostMatchPositioning::whereBelongsTo($league)->whereBelongsTo($season)->whereHas('user.country', function ($query) use ($request) {
        //             $query->where('id', $request->user()->country->id);
        //         })->orderBy('current_country_position');
        //         break;
        // }

        // return $postMatchPositionings->get();

        $postMatchPositionings = RankService::getPrectionUsers($league, $season);
        switch ($request->tops) {
            case 'country':
                $postMatchPositionings = RankService::getPrectionUsers($league, $season, 'country', $request->user()->country->id);
                break;
        }

        return $postMatchPositionings;
    }
}
