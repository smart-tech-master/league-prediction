<?php

namespace App\Services;

use App\Jobs\PostMatchPositioning\ProcessFixture;
use App\Jobs\PostMatchPositioning\ProcessPredictionPoints;
use App\Models\ApiFootball\Fixture;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Models\Country;
use App\Models\PostMatchPositioning;
use App\Models\User;
use App\Models\LeagueUser;
use App\Models\Prediction;
use App\Services\ApiFootball\LeagueService;
use App\Services\ApiFootball\RoundService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostMatchPositioningService
{
    public function getFixtures()
    {
        $apiFootball = new \ApiFootball();

        return Fixture::whereIn('short_status', $apiFootball::fixtureFinishedStates())
            ->whereNotNull('finished_at')
            ->get()
            ->filter(function ($fixture) {
                if (Carbon::now()->diffInMinutes(Carbon::parse($fixture->finished_at), false) > -33600) {
                    return true;
                }
            });
    }

    public function init()
    {
        set_time_limit(36000); // 10hours

        $apiFootball = new \ApiFootball();

        $fixtures = Fixture::whereIn('short_status', $apiFootball::fixtureFinishedStates())
            ->whereNotNull('finished_at')
            ->get()
            ->filter(function ($fixture) {
                if (Carbon::now()->diffInMinutes(Carbon::parse($fixture->finished_at), false) == -1) {
                    return true;
                }
            });

        foreach ($fixtures as $fixture) {
            ProcessPredictionPoints::dispatch($fixture);
            ProcessFixture::dispatch($fixture);
        }
    }

    public function processPredictionPoints(Fixture $fixture)
    {
        $fixture->predictions()->chunk(100, function ($predictions){
            foreach ($predictions as $prediction){
                PredictionService::storePoints($prediction);
                // echo $prediction->user->id;
                // if(!$prediction->user){

                //     print_r($prediction->id);exit;
                // }
                // $postMatchPositioning = PostMatchPositioning::whereBelongsTo($prediction->user)->whereBelongsTo($prediction->season)->whereBelongsTo($prediction->league)->first();
                // if($postMatchPositioning){
                //     $postMatchPositioning->total_points = (new LeagueService())->points($postMatchPositioning->user, $postMatchPositioning->league, $postMatchPositioning->season);
                //     $postMatchPositioning->update();
                // }
            }
        });
//        foreach ($fixture->predictions()->get() as $prediction) {
//            PredictionService::storePoints($prediction);
//            $postMatchPositioning = PostMatchPositioning::whereBelongsTo($prediction->user)->whereBelongsTo($prediction->season)->whereBelongsTo($prediction->league)->first();
//            if($postMatchPositioning){
//                $postMatchPositioning->total_points = $postMatchPositioning->total_points + $prediction->points;
//                $postMatchPositioning->update();
//            }
//        }
    }

    public function processFixture(Fixture $fixture)
    {
        return DB::transaction(function () use ($fixture) {

            $position = 1;
            PostMatchPositioning::whereBelongsTo($fixture->league)->whereBelongsTo($fixture->season)->orderByDesc('total_points')->chunk(100, function ($postMatchPositionings) use ($fixture, &$position){
                foreach ($postMatchPositionings as $postMatchPositioning){
                    $postMatchPositioning->previous_world_position = $postMatchPositioning->current_world_position;
                    $postMatchPositioning->current_world_position = $position;
                    //check last fixture
//                    if ($fixture->round->fixtures()->orderByDesc('timestamp')->first()->id == $fixture->id) {
//                        $postMatchPositioning->total_points_on_last_round = (new RoundService())->points($postMatchPositioning->user, $fixture->round);
//                    }
                    $postMatchPositioning->update();

                    $position++;
                }
            });

            foreach (Country::groupBy('continent')->get() as $country) {
                $position = 1;
                PostMatchPositioning::whereBelongsTo($fixture->league)->whereBelongsTo($fixture->season)
                    ->whereHas('user.country', function ($query) use ($country) {
                        $query->where('continent', $country->continent);
                    })
                    ->orderByDesc('total_points')->chunk(100, function ($postMatchPositionings) use (&$position) {
                    foreach ($postMatchPositionings as $key => $postMatchPositioning) {
                        $postMatchPositioning->previous_continent_position = $postMatchPositioning->current_continent_position;
                        $postMatchPositioning->current_continent_position = $position;
                        $postMatchPositioning->update();

                        $position++;
                    }
                });
            }

            foreach (Country::get() as $country) {
                $position = 1;
                PostMatchPositioning::whereBelongsTo($fixture->league)->whereBelongsTo($fixture->season)
                    ->whereHas('user.country', function ($query) use ($country) {
                        $query->where('id', $country->id);
                    })
                    ->orderByDesc('total_points')->chunk(100, function ($postMatchPositionings, &$position) {
                    foreach ($postMatchPositionings as $key => $postMatchPositioning) {
                        $postMatchPositioning->previous_country_position = $postMatchPositioning->current_country_position;
                        $postMatchPositioning->current_country_position = $position;
                        $postMatchPositioning->update();

                        $position++;
                    }
                });
            }
//
//            $getWorldUsers = RankService::getUsers($fixture->league, $fixture->season);
//            $serializedWorldUsers = RankService::serialize($getWorldUsers);
//
//            foreach ($serializedWorldUsers as $serializedWorldUser) {
//
//                $user = User::select(['id'])->withTrashed()->findOrFail($serializedWorldUser['user']['id']);
//
//                $postMatchPosition = PostMatchPositioning::whereBelongsTo($user)
//                    ->whereBelongsTo($fixture->league)
//                    ->whereBelongsTo($fixture->season)
//                    ->firstOr(function () use ($fixture, $user) {
//                        return PostMatchPositioning::forceCreate([
//                            'user_id' => $user->id,
//                            'league_id' => $fixture->league->id,
//                            'season_id' => $fixture->season->id,
//                        ]);
//                    });
//
//                $postMatchPosition->total_points = RankService::points($user, $postMatchPosition->league, $postMatchPosition->season);
//
//                //check last fixture
//                if ($fixture->round->fixtures()->orderByDesc('timestamp')->first()->id == $fixture->id) {
//                    $postMatchPosition->total_points_on_last_round = (new RoundService())->points($user, $fixture->round);
//                }
//
//                $postMatchPosition->previous_world_position = $postMatchPosition->current_world_position;
//                $postMatchPosition->current_world_position = $serializedWorldUsers->firstWhere('user.id', $user->id)['position'] ?? null;
//                $postMatchPosition->total_world_users = $serializedWorldUsers->count();
//                $postMatchPosition->update();
//            }
//
//            foreach (Country::select(['id', 'continent'])->get() as $country) {
//                $getContinentUsers = $getWorldUsers->where('country.continent', $country->continent);
//                $serializedContinentUsers = RankService::serialize($getContinentUsers);
//
//                foreach ($serializedContinentUsers as $serializedContinentUser) {
//
//                    $user = User::select(['id'])->withTrashed()->findOrFail($serializedContinentUser['user']['id']);
//
//                    $postMatchPosition = PostMatchPositioning::whereBelongsTo($user)
//                        ->whereBelongsTo($fixture->league)
//                        ->whereBelongsTo($fixture->season)
//                        ->firstOr(function () use ($fixture, $user) {
//                            return PostMatchPositioning::forceCreate([
//                                'user_id' => $user->id,
//                                'league_id' => $fixture->league->id,
//                                'season_id' => $fixture->season->id,
//                            ]);
//                        });
//
//                    $postMatchPosition->previous_continent_position = $postMatchPosition->current_continent_position;
//                    $postMatchPosition->current_continent_position = $serializedContinentUsers->firstWhere('user.id', $user->id)['position'] ?? null;
//                    $postMatchPosition->total_continent_users = $serializedContinentUsers->count();
//                    $postMatchPosition->update();
//                }
//
//                $getCountryUsers = $getWorldUsers->where('country.id', $country->id);
//                $serializedCountryUsers = RankService::serialize($getCountryUsers);
//
//                foreach ($serializedCountryUsers as $serializedCountryUser) {
//
//                    $user = User::select(['id'])->withTrashed()->findOrFail($serializedCountryUser['user']['id']);
//
//                    $postMatchPosition = PostMatchPositioning::whereBelongsTo($user)
//                        ->whereBelongsTo($fixture->league)
//                        ->whereBelongsTo($fixture->season)
//                        ->firstOr(function () use ($fixture, $user) {
//                            return PostMatchPositioning::forceCreate([
//                                'user_id' => $user->id,
//                                'league_id' => $fixture->league->id,
//                                'season_id' => $fixture->season->id,
//                            ]);
//                        });
//
//                    $postMatchPosition->previous_country_position = $postMatchPosition->current_country_position;
//                    $postMatchPosition->current_country_position = $serializedCountryUsers->firstWhere('user.id', $user->id)['position'] ?? null;
//                    $postMatchPosition->total_country_users = $serializedCountryUsers->count();
//                    $postMatchPosition->update();
//                }
//            }

            //$fixture->calculate_post_match_positioning = true;
            //$fixture->update();
        });
    }

    public function processSubscription(User $user, League $league, Season $season)
    {
        $postMatchPositioning = PostMatchPositioning::whereBelongsTo($user)
            ->whereBelongsTo($league)
            ->whereBelongsTo($season)
            ->firstOr(function () use ($user, $league, $season) {

                $worldUsers = PostMatchPositioning::whereBelongsTo($league)->whereBelongsTo($season);
                $getLastWorldUser = $worldUsers->orderByDesc('current_world_position')->first();
                $getTotalWorldUsers = $worldUsers->count();

                $continentUsers = PostMatchPositioning::whereBelongsTo($league)->whereBelongsTo($season)->whereHas('user.country', function ($query) use ($user) {
                    $query->where('continent', $user->country->continent);
                });
                $getLastContinentUser = $continentUsers->orderByDesc('current_continent_position')->first();
                $getTotalContinentUsers = $continentUsers->count();

                $countryUsers = PostMatchPositioning::whereBelongsTo($league)->whereBelongsTo($season)->whereHas('user.country', function ($query) use ($user) {
                    $query->where('id', $user->country->id);
                });
                $getLastCountryUser = $countryUsers->orderByDesc('current_country_position')->first();
                $getTotalCountryUsers = $countryUsers->count();

                return PostMatchPositioning::forceCreate([
                    'user_id' => $user->id,
                    'league_id' => $league->id,
                    'season_id' => $season->id,
                    'total_points' => 0,
                    'total_points_on_last_round' => 0,
                    'current_world_position' => ($getLastWorldUser->current_world_position ?? 0) + 1,
                    'total_world_users' => $getTotalWorldUsers + 1,
                    'current_continent_position' => ($getLastContinentUser->current_continent_position ?? 0) + 1,
                    'total_continent_users' => $getTotalContinentUsers + 1,
                    'current_country_position' => ($getLastCountryUser->current_country_position ?? 0) + 1,
                    'total_country_users' => $getTotalCountryUsers + 1,
                ]);

//                $getWorldUsers = RankService::getUsers($league, $season);
//                $serializedWorldUsers = RankService::serialize($getWorldUsers);
//                $getContinentUsers = $getWorldUsers->where('country.continent', $user->country->continent);
//                $serializedContinentUsers = RankService::serialize($getContinentUsers);
//                $getCountryUsers = $getWorldUsers->where('country.id', $user->country->id);
//                $serializedCountryUsers = RankService::serialize($getCountryUsers);
//                return PostMatchPositioning::forceCreate([
//                    'user_id' => $user->id,
//                    'league_id' => $league->id,
//                    'season_id' => $season->id,
//                    'total_points' => 0,
//                    'total_points_on_last_round' => 0,
//                    'current_world_position' => $serializedWorldUsers->firstWhere('user.id', $user->id)['position'] ?? null,
//                    'total_world_users' => $serializedWorldUsers->count(),
//                    'current_continent_position' => $serializedContinentUsers->firstWhere('user.id', $user->id)['position'] ?? null,
//                    'total_continent_users' => $serializedContinentUsers->count(),
//                    'current_country_position' => $serializedCountryUsers->firstWhere('user.id', $user->id)['position'] ?? null,
//                    'total_country_users' => $serializedCountryUsers->count(),
//                ]);
            });

        $worldUsers = PostMatchPositioning::whereBelongsTo($league)->whereBelongsTo($season);
        $getTotalWorldUsers = $worldUsers->count();

        $continentUsers = PostMatchPositioning::whereBelongsTo($league)->whereBelongsTo($season)->whereHas('user.country', function ($query) use ($user) {
            $query->where('continent', $user->country->continent);
        });
        $getTotalContinentUsers = $continentUsers->count();

        $countryUsers = PostMatchPositioning::whereBelongsTo($league)->whereBelongsTo($season)->whereHas('user.country', function ($query) use ($user) {
                $query->where('id', $user->country->id);
            });
        $getTotalCountryUsers = $countryUsers->count();

        $worldUsers->chunk(100, function ($users) use ($getTotalWorldUsers) {
            foreach ($users as $user) {
                $user->total_world_users = $getTotalWorldUsers;
                $user->update();
            }
        });

        $continentUsers->chunk(100, function ($users) use ($getTotalContinentUsers) {
            foreach ($users as $user) {
                $user->total_continent_users = $getTotalContinentUsers;
                $user->update();
            }
        });

        $countryUsers->chunk(100, function ($users) use ($getTotalCountryUsers) {
            foreach ($users as $user) {
                $user->total_country_users = $getTotalCountryUsers;
                $user->update();
            }
        });

        return $postMatchPositioning;
    }

    public function execUpdatePoint()
    {
        $users = User::whereIn('id', Prediction::groupBy("user_id")->pluck('user_id'))->get();

        foreach ($users as $key => $user) 
        {
            $leagues = League::whereIn('id', LeagueUser::where('user_id', $user->id)->pluck('league_id'))->get();

            foreach ($leagues as $key => $league) 
            {
                $this->updatePoint($user, $league, Season::first());
            }
        }

        foreach ($users as $key => $user) 
        {
            $leagues = League::whereIn('id', LeagueUser::where('user_id', $user->id)->pluck('league_id'))->get();

            foreach ($leagues as $key => $league) 
            {
                $this->updateTotalUsers($user, $league, Season::first());
                $this->updateWorldPosition($user, $league, Season::first());
                $this->updateCountryPosition($user, $league, Season::first());
            }
        }

    }

    public function updatePoint(User $user, League $league, Season $season) 
    {
        $postMatchPositioning = PostMatchPositioning::whereBelongsTo($user)
            ->whereBelongsTo($league)
            ->whereBelongsTo($season)
            ->firstOr(function () use ($user, $league, $season) {
                return PostMatchPositioning::forceCreate([
                    'user_id' => $user->id,
                    'league_id' => $league->id,
                    'season_id' => $season->id,
                    'total_points' => 0,
                    'total_points_on_last_round' => 0,
                    'current_world_position' => 0,
                    'total_world_users' => 0,
                    'current_continent_position' => 0,
                    'total_continent_users' => 0,
                    'current_country_position' => 0,
                    'total_country_users' => 0,
                ]);
            });

        $postMatchPositioning->total_points = RankService::points($user, $league, $season);
        $postMatchPositioning->total_points_on_last_round = RankService::getPointsFromLastRoundInPredictionTable($user, $league, $season);
        $postMatchPositioning->update();
    }

    public function updateTotalUsers(User $user, League $league, Season $season) 
    {
        $worldUsers = PostMatchPositioning::whereBelongsTo($league)->whereBelongsTo($season);
        $getTotalWorldUsers = $worldUsers->count();

        $countryUsers = PostMatchPositioning::whereBelongsTo($league)->whereBelongsTo($season)->whereHas('user.country', function ($query) use ($user) {
            $query->where('id', $user->country->id);
        });
        $getTotalCountryUsers = $countryUsers->count();


        // world
        $worldUsers->chunk(100, function ($users) use ($getTotalWorldUsers) {
            foreach ($users as $user) {
                $user->total_world_users = $getTotalWorldUsers;
                $user->update();
            }
        });

        // country
        $countryUsers->chunk(100, function ($users) use ($getTotalCountryUsers) {
            foreach ($users as $user) {
                $user->total_country_users = $getTotalCountryUsers;
                $user->update();
            }
        });


    }

    public function updateWorldPosition(User $user, League $league, Season $season) 
    {
        $worldUsers = PostMatchPositioning::whereBelongsTo($league)->whereBelongsTo($season)->orderByDesc('total_points')->get();

        $worldPosition = 0;
        foreach ($worldUsers as $key => $worldUser) 
        {
            $worldPosition++;
            if($user->id == $worldUser->user_id)
            {
                break;
            }
        }

        $postMatchPositioning = PostMatchPositioning::whereBelongsTo($user)->whereBelongsTo($league)->whereBelongsTo($season)->first();
        $postMatchPositioning->current_world_position = $worldPosition;
        $postMatchPositioning->update();
    }

    public function updateCountryPosition(User $user, League $league, Season $season) 
    {
        $countryUsers = PostMatchPositioning::whereBelongsTo($league)->whereBelongsTo($season)->orderByDesc('total_points')->whereHas('user.country', function ($query) use ($user) {
            $query->where('id', $user->country->id);
        })->get();

        $countryPosition = 0;
        foreach ($countryUsers as $key => $countryUser) 
        {
            $countryPosition++;
            if($user->id == $countryUser->user_id)
            {
                break;
            }
        }

        $postMatchPositioning = PostMatchPositioning::whereBelongsTo($user)->whereBelongsTo($league)->whereBelongsTo($season)->first();
        $postMatchPositioning->current_country_position = $countryPosition;
        $postMatchPositioning->update();
    }

    public static function getPoints(User $user, League $league, Season $season)
    {
        $record = PostMatchPositioning::whereBelongsTo($user)->whereBelongsTo($league)->whereBelongsTo($season)->first();

        return (int)( $record ? $record->total_points : 0);
    }

    public static function getCurrentAndLastRoundPoints(User $user, League $league, Season $season)
    {
        $record = PostMatchPositioning::whereBelongsTo($user)->whereBelongsTo($league)->whereBelongsTo($season)->first();

        $points = (int)( $record ? $record->total_points : 0);
        $lastPoints = (int)( $record ? $record->total_points_on_last_round : 0);
        return ['points' => $points, 'lastPoints' => $lastPoints];
    }

    public static function getPosition(User $user, League $league, Season $season, $userIds, $points)
    {
        $records = PostMatchPositioning::whereBelongsTo($league)->whereBelongsTo($season)->whereIn('user_id', $userIds);
        $onePosition = count( $records->where('total_points', '>', $points)->get() );
        $secondPosition = count( $records->where('total_points', '=', $points)->where('user_id', '<', $user->id)->get() );

        $position = $onePosition + $secondPosition + 1;

        return (int)$position;
    }

}
