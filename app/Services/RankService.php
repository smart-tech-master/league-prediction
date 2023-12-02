<?php

namespace App\Services;

use App\Http\Resources\ApiFootball\LeagueResource;
use App\Http\Resources\ApiFootball\SeasonResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\Users\SimpleResource;
use App\Models\ApiFootball\Fixture;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Round;
use App\Models\ApiFootball\Season;
use App\Models\Country;
use App\Models\User;
use App\Models\Prediction;
use App\Services\ApiFootball\RoundService;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class RankService
{

    public static function rank(User $user, $league, $season)
    {
        // $data = [];

        // $leagues = $user->leagues();

        // if (!is_null($league)) {
        //     $leagues = $leagues->whereId($league);
        // }

        // foreach ($leagues->wherePivot('season_id', $season)->get() as $league) {
        //     $season = Season::findOrFail($league->pivot->season_id);

        //     $users = [
        //         'world' => self::users($league, $season),
        //         'continent' => self::users($league, $season, 'continent', $user->country->continent),
        //         'country' => self::users($league, $season, 'country', $user->country),
        //     ];

        //     $data[] = [
        //         'league' => LeagueResource::make($league),
        //         'season' => SeasonResource::make($season),
        //         'world' => [
        //             'position' => $users['world']->firstWhere('user.id', $user->id)['position'] ?? null,
        //             'users' => $users['world']->count(),
        //             // 'tops' => self::tops($league, $season)->take(100),
        //         ],
        //         'continent' => [
        //             'position' => $users['continent']->firstWhere('user.id', $user->id)['position'] ?? null,
        //             'users' => $users['continent']->count(),
        //             // 'tops' => self::tops($league, $season, 'continent', $user->country->continent)->take(100),
        //         ],
        //         'country' => [
        //             'name' => $user->country->name,
        //             'position' => $users['country']->firstWhere('user.id', $user->id)['position'] ?? null,
        //             'users' => $users['country']->count(),
        //             // 'tops' => self::tops($league, $season, 'country', $user->country)->take(100),
        //         ],
        //         'points' => self::points($user, $league, $season),
        //     ];
        // }

        // return $data;

        $data = [];

        $leagues = self::userPredictedLeagues($user);

        foreach ($leagues as $league) {
            $users = [
                'world' => self::getPrectionUsers($league, $season),
                'country' => self::getPrectionUsers($league, $season, 'country', $user->country->id),
            ];

            $data[] = [
                'league' => LeagueResource::make($league),
                'season' => SeasonResource::make($season),
                'world' => [
                    'position' => $users['world']->firstWhere('user.id', $user->id)['position'] ?? null,
                    'users' => $users['world']->count(),
                    'total' => $users['world']->count(),
                ],
                'country' => [
                    'name' => $user->country->name,
                    'position' => $users['country']->firstWhere('user.id', $user->id)['position'] ?? null,
                    'users' => $users['country']->count(),
                    'total' => $users['country']->count(),
                ],
                'points' => self::points($user, $league, $season),
            ];
        }

        return $data;
    }


    public static function profileRank(User $user, $league, $season)
    {
        $data = [];

        $leagues = self::userPredictedLeagues($user);

        
        foreach ($leagues as $league) {
            $points = PostMatchPositioningService::getPoints($user, $league, $season);
            $usersAndPosition = self::getCompetitonUsersAndPosition($user, $league, $season, $user->country->id, $points);
            // $data[] = self::getCompetitonUsers($league, $season, $haystack = 'world', $needle = null);

            // $users = [
            //     'world' => self::getPrectionUsers($league, $season),
            //     'country' => self::getPrectionUsers($league, $season, 'country', $user->country->id),
            // ];

            $data[] = [
                'league' => LeagueResource::make($league),
                'season' => SeasonResource::make($season),
                'world' => [
                    'position' => $usersAndPosition['world']['position'],
                    'users' => $usersAndPosition['world']['count'],
                    'total' => $usersAndPosition['world']['count'],
                ],
                'country' => [
                    'name' => $user->country->name,
                    'position' => $usersAndPosition['country']['position'],
                    'users' => $usersAndPosition['country']['count'],
                    'total' => $usersAndPosition['country']['count'],
                ],
                'points' => $points,
            ];
        }

        return $data;
    }

    public static function getCompetitonUsersAndPosition($user, $league, $season, $needle = null, $points = 0) {
        $users = User::publicUser()->whereNotNull('country_id')->whereIn('id', Prediction::where("league_id", $league->id)->groupBy("user_id")->pluck('user_id'))->with(['leagues' => function ($query) use ($league, $season) {
            $query->wherePivot('league_id', $league->id)->wherePivot('season_id', $season->id);
        }]);

        $worldUsers = $users->get()->pluck('id')->toArray();
        $countryUsers = $users->where('country_id', $needle)->get()->pluck('id')->toArray();

        return [
            'world' => [
                'count' => count($worldUsers),
                'position' => PostMatchPositioningService::getPosition($user, $league, $season, $worldUsers, $points)
            ],
            'country' => [
                'count' => count($countryUsers),
                'position' => PostMatchPositioningService::getPosition($user, $league, $season, $countryUsers, $points)
            ]
        ];
    }

    public static function users(League $league, Season $season, $haystack = 'world', $needle = null)
    {
        $users = User::publicUser();

        switch ($haystack) {
            case 'continent':
                $users = $users->whereIn('country_id', Country::whereContinent($needle)->get()->pluck('id')->toArray());
                break;
            case 'country':
                $users = $users->whereBelongsTo($needle);
                break;
        }

        return $users->get()
            ->filter(function ($user) use ($league, $season) {
                return $user->leagues()->whereId($league->id)
                    ->wherePivot('season_id', $season->id)
                    ->first();
            })
            ->map(function ($user) use ($league, $season) {
                return [
                    'user' => SimpleResource::make($user)->resolve(),
                    'points' => self::points($user, $league, $season),
                ];
            })
            ->sortByDesc('points')
            ->values()
            ->map(function ($item, $key) {
                return array_merge($item, ['position' => $key + 1]);
            });
    }

    public static function points(User $user, League $league, Season $season)
    {
        $points = $user->predictions()->select('points')->whereBelongsTo($league)->whereBelongsTo($season)->sum('points');
        return (int)$points;
    }

    public static function tops($league, $season, $haystack = 'world', $needle = null)
    {
        return self::users($league, $season, $haystack, $needle)
            ->map(function ($item) use ($league, $season, $haystack, $needle) {
                return array_merge($item, [
                    'most_recent_position' => self::mostRecentPosition($item, $league, $season, $haystack, $needle),
                    'points_from_last_round' => self::pointsFromLastRound($item, $league, $season),
                ]);
            })
            ->values();
    }

    private static function mostRecentPosition($item, $league, $season, $haystack, $needle)
    {
        $apiFootball = new \ApiFootball();

        return User::find($item['user']['id'])
            ->predictions()->with(['fixture'])->whereBelongsTo($league)->whereBelongsTo($season)->get()
            ->whereIn('fixture.short_status', $apiFootball::fixtureFinishedStates()) // last prediction by user which short_status = 'FT'
            ->whereNotNull('fixture.finished_at')
            ->sortByDesc('fixture.finished_at') // most recent fixture
            ->first()->{$haystack . '_position'} ?? // get haystack position
            //otherwise position is during registration
//            (self::users($league, $season, $haystack, $needle)
            /*(self::getUsersOrderByPointsDesc($league, $season, $haystack, $needle)
                    ->filter(function ($item2) use ($item) {
                        return $item2['user']['id'] < $item['user']['id'];
                    })
                    ->sortBy('user.id')
                    ->count() + 1)*/
            self::getUsers($league, $season, $haystack, $needle);
        //(User::publicUser()->where('id', '<', $item['user']['id'])->orderBy('id')->count() + 1);
    }

    public static function pointsFromLastRound($item, $league, $season)
    {
        $apiFootball = new \ApiFootball();

        $fixture = Fixture::whereBelongsTo($league)->whereBelongsTo($season)->whereIn('short_status', $apiFootball::fixtureFinishedStates())->whereNotNull('finished_at')->orderByDesc('finished_at')->first(); // last match

        $round = Round::whereBelongsTo($league)->whereBelongsTo($season)->where('id', '<', $fixture->round->id ?? 0)->orderBy('id')->first(); // previous round

        if ($round) {
            return (new RoundService())->points(User::find($item['user']['id']), $round);
        }

        return null;
    }

    public static function toppers(League $league, Season $season, $haystack = 'world', $needle = null)
    {
        $users = self::getUsers($league, $season, $haystack, $needle);
        return $users
            ->take(100)
            ->map(function ($item) use ($league, $season, $haystack, $needle, $users) {
                $user = User::findOrFail($item['user']['id']);
                return [
                    'user' => $item['user'],
                    'points' => $item['points'],
                    'position' => $item['position'],
                    'most_recent_position' => self::getMostRecentPosition($users, $user, $league, $season, $haystack, $needle),
                    'points_from_last_round' => self::getPointsFromLastRound($user, $league, $season),
                ];
            });
    }

    public static function getUsers(League $league, Season $season, $haystack = 'world', $needle = null)
    {
        $USERS = [];

        $users = User::publicUser()->whereNotNull('country_id')->with(['leagues' => function ($query) use ($league, $season) {
            $query->wherePivot('league_id', $league->id)->wherePivot('season_id', $season->id);
        }]);

        switch ($haystack) {
            case 'continent':
                $users = $users->whereIn('country_id', Country::whereContinent($needle)->get()->pluck('id')->toArray());
                break;
            case 'country':
                $users = $users->whereBelongsTo($needle);
                break;
        }

        $users->chunk(500, function ($users) use ($league, $season, &$USERS){
            foreach ($users as $user){
                $USERS[] = [
                    'user' => SimpleResource::make($user)->resolve(),
                    'country' => CountryResource::make($user->country)->resolve(),
                    'points' => self::points($user, $league, $season),
                ];
            }
        });

        return collect($USERS);

        /*return $users->get()
            ->map(function ($user) use ($league, $season, $haystack, $needle) {
                return [
                    'user' => SimpleResource::make($user)->resolve(),
                    'country' => CountryResource::make($user->country)->resolve(),
                    'points' => self::points($user, $league, $season),
                ];
            })->sortByDesc('points')
            ->values()
            ->map(function ($item, $key) {
                return array_merge($item, ['position' => $key + 1]);
            })*/ ;
    }

    public static function getPointsFromLastRound($user, $league, $season)
    {
        $apiFootball = new \ApiFootball();

        $fixture = Fixture::whereBelongsTo($league)->whereBelongsTo($season)->whereIn('short_status', $apiFootball::fixtureFinishedStates())->whereNotNull('finished_at')->orderByDesc('finished_at')->first(); // last match

        $round = Round::whereBelongsTo($league)->whereBelongsTo($season)->where('id', '<', $fixture->round->id ?? 0)->orderBy('id')->first(); // previous round

        if ($round) {
            return (new RoundService())->points($user, $round);
        }

        return null;
    }

    public static function getMostRecentPosition($users, $user, $league, $season, $haystack, $needle)
    {
        $apiFootball = new \ApiFootball();

        return $user->predictions()->with(['fixture' => function ($query) use ($apiFootball) {
            $query->whereIn('short_status', $apiFootball::fixtureFinishedStates())
                ->whereNotNull('finished_at')->orderByDesc('finished_at');
        }])->whereBelongsTo($league)->whereBelongsTo($season)//->get()
        //->whereIn('fixture.short_status', $apiFootball::fixtureFinishedStates()) // last prediction by user which short_status = 'FT'
        //->whereNotNull('fixture.finished_at')
        // ->sortByDesc('fixture.finished_at') // most recent fixture
        ->first()->{$haystack . '_position'} ?? // get haystack position
            //otherwise position is during registration
//            (self::users($league, $season, $haystack, $needle)
            ($users->where('user.id', '<', $user->id)
//                    ->filter(function ($item2) use ($item) {
//                        return $item2['user']['id'] < $item['user']['id'];
//                    })
                    ->sortBy('user.id')
                    ->count() + 1);
        //(User::publicUser()->where('id', '<', $item['user']['id'])->orderBy('id')->count() + 1);
    }

    public static function serialize($getUsers)
    {
        return $getUsers
            ->sortByDesc('points')
            ->values()
            ->map(function ($item, $key) {
                return array_merge($item, ['position' => $key + 1]);
            });
    }

    public static function getPrectionUsers($league, $season, $haystack = 'world', $needle = null) {
        $users = User::publicUser()->whereNotNull('country_id')->whereIn('id', Prediction::where("league_id", $league->id)->groupBy("user_id")->pluck('user_id'))->with(['leagues' => function ($query) use ($league, $season) {
            $query->wherePivot('league_id', $league->id)->wherePivot('season_id', $season->id);
        }]);

        switch ($haystack) {
            // case 'continent':
            //     $users = $users->whereIn('country_id', Country::whereContinent($needle)->get()->pluck('id')->toArray());
            //     break;
            case 'country':
                $users = $users->where('country_id', $needle);
                break;
        }

        return $users->get()
                    ->map(function ($user) use ($league, $season) {
                        $points = PostMatchPositioningService::getCurrentAndLastRoundPoints($user, $league, $season);
                        return [
                            'user' => SimpleResource::make($user)->resolve(),
                            // 'points' => self::points($user, $league, $season),
                            // 'current_round_points' => self::getPointsFromLastRoundInPredictionTable($user, $league, $season),
                            // 'points_from_last_round' => self::getPointsFromLastRoundInPredictionTable($user, $league, $season),
                            'current_round_points' => $points['lastPoints'],
                            'points_from_last_round' => $points['lastPoints'],
                            'points' => $points['points'],
                        ];
                    })
                    ->sortByDesc('points')
                    ->values()
                    ->map(function ($item, $key) {
                        return array_merge($item, ['position' => $key + 1]);
                    });
    }

    public static function getLeagueUsers($league, $season, $haystack = 'world', $needle = null) {
        $users = User::publicUser()->whereNotNull('country_id')->whereIn('id', Prediction::where("league_id", $league->id)->groupBy("user_id")->pluck('user_id'))->with(['leagues' => function ($query) use ($league, $season) {
            $query->wherePivot('league_id', $league->id)->wherePivot('season_id', $season->id);
        }]);

        switch ($haystack) {
            case 'country':
                $users = $users->where('country_id', $needle);
                break;
        }

        return $users->get()
                    ->map(function ($user) use ($league, $season) {
                        return [
                            'user' => SimpleResource::make($user)->resolve(),
                            // 'points' => self::points($user, $league, $season),
                            'points' => PostMatchPositioningService::getPoints($user, $league, $season),
                        ];
                    })
                    ->sortByDesc('points')
                    ->values()
                    ->map(function ($item, $key) {
                        return array_merge($item, ['position' => $key + 1]);
                    });
    }

    public static function getPointsFromLastRoundInPredictionTable($user, $league, $season) {
        $points = 0;
        $currentRound = RoundService::getCurrentRound($league, $season);

        if( !$currentRound ){
            return $points;
        }

        $currentRoundFirstFixture = Fixture::whereBelongsTo($league)->whereBelongsTo($currentRound)->orderBy('timestamp')->first();
        if(!is_null($currentRoundFirstFixture) && Carbon::now()->lessThan($currentRoundFirstFixture->timestamp)){
            $currentRound->id = $currentRound->id - 1;
        }

        $fixtures = Fixture::whereBelongsTo($league)->whereBelongsTo($season)->where("round_id", $currentRound->id)->pluck('id');

        $predictions = Prediction::where("user_id", $user->id)->whereIn("fixture_id", $fixtures)->get();

        foreach ($predictions as $key => $prediction) {
            if(!is_null($prediction->points)) {
                $points += $prediction->points;
            }
        }

        return $points;
    }

    public static function userPredictedLeagues($user) {
        $leagues = League::whereIn('id', Prediction::where("user_id", $user->id)->groupBy("league_id")->pluck('league_id'))->get();
        return $leagues;
    }

    public static function pointsByRound(User $user, League $league, Round $round)
    {
        $points = $user->predictions()->select('points')->whereBelongsTo($league)->whereBelongsTo($round)->sum('points');
        return (int)$points;
    }

}
