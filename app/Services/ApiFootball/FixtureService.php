<?php

namespace App\Services\ApiFootball;

use App\Models\ApiFootball\Fixture;
use App\Models\ApiFootball\Round;
use App\Models\ApiFootball\Team;
use App\Models\ApiFootball\Venue;
use App\Models\ApiFootball\FixturePredictionStatistics;
use App\Models\Prediction;
use App\Services\PredictionService;
use App\Services\RankService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FixtureService
{
    public function init()
    {
        $apiFootball = new \ApiFootball();
        // foreach (Round::all() as $round) {
            
        //     if (!empty($round->keywords) || !is_null($round->keywords)) {
        //         if (in_array($round->league_id, [1, 39])) { // World cup, Premier league
        //             $this->fixturesByKeywords($apiFootball, $round);
        //         } elseif ($round->league_id == 2) {
                    
        //             if (in_array($round->slug, [
        //                 'round-of-16-part-1',
        //                 'round-of-16-part-2',
        //                 'quarter-finals-part-1',
        //                 'quarter-finals-part-2',
        //             ])) {
        //                 $this->fixturesById($apiFootball, $round);
        //             } else {
        //                 $this->fixturesByKeywords($apiFootball, $round);
        //             }

        //             if (in_array($round->slug, ['semi-finals-part-1', 'semi-finals-part-2','final'])) {
        //                 $round->keywords = [$round->name];
    
        //                 if($round->slug == 'semi-finals-part-1') {
        //                     $range = [0 , 2];
        //                     $this->fixturesByKeywordsWithRange($apiFootball, $round, $range);
        //                 }
        
        //                 if($round->slug == 'semi-finals-part-2') {
        //                     $range = [1 , 3];
        //                     $this->fixturesByKeywordsWithRange($apiFootball, $round, $range);
        //                 }
        
        //                 if($round->slug == 'final') {
        //                     $this->fixturesByKeywords($apiFootball, $round);
        //                 }
        //             }

        //         }
                
        //     }
        // }

        foreach (Round::all() as $round) 
        {
            
            if (empty($round->keywords)) 
            {
                $this->fixturesBySlug($apiFootball, $round);
            }
            else
            {
                $this->fixturesById($apiFootball, $round);
            }
            
        }

    }

    private function fixturesBySlug($apiFootball, $round)
    {
        $params = [
            'league' => $round->league->id,
            'season' => $round->season->year,
            'round' => $round->slug
        ];

        $response = $apiFootball::fixtures($params)->object()->response ?? [];

        $this->create($apiFootball, $round, $response);
    }

    private function fixturesByKeywords($apiFootball, $round)
    {
        foreach ($round->keywords as $keyword) {
            $params = [
                'league' => $round->league->id,
                'season' => $round->season->year,
                'round' => $keyword
            ];

            $response = $apiFootball::fixtures($params)->object()->response ?? [];

            $this->create($apiFootball, $round, $response);
        }
    }

    private function fixturesById($apiFootball, $round)
    {
        foreach ($round->keywords as $keyword) {
            $params = [
                'id' => $keyword,
            ];

            $response = $apiFootball::fixtures($params)->object()->response ?? [];

            $this->create($apiFootball, $round, $response);
        }
    }

    private function fixturesByKeywordsWithRange($apiFootball, $round, $range)
    {
        foreach ($round->keywords as $keyword) {
            $params = [
                'league' => $round->league->id,
                'season' => $round->season->year,
                'round' => $keyword
            ];
            
            $response = $apiFootball::fixtures($params)->object()->response ?? [];

            foreach($range as $key) {
                $fixturesByRange[] = $response[$key];
            }

            $this->create($apiFootball, $round, $fixturesByRange);
        }
    }

    private function create($apiFootball, $round, $response)
    {
        foreach ($response as $value) {
            // print_r("fixture id : ".$value->fixture->id);
            // print_r("\n");
            // print_r("league id : ".$round->league->id);
            // print_r("\n");
            $fixture = Fixture::whereId($value->fixture->id)->firstOr(function () use ($round, $value) {
                return Fixture::forceCreate([
                    'id' => $value->fixture->id,
                    'timezone' => $value->fixture->timezone,
                    'timestamp' => Carbon::parse($value->fixture->date),
                    'long_status' => $value->fixture->status->long,
                    'short_status' => $value->fixture->status->short,
                    'league_round' => $value->league->round,
                    'league_id' => $round->league->id,
                    'season_id' => $round->season->id,
                    'round_id' => $round->id,
                    'venue_id' => $this->createVenue($value->fixture->venue),
                    'finished_at' => !is_null($value->fixture->status->elapsed) ? Carbon::parse($value->fixture->date)->addMinutes($value->fixture->status->elapsed) : null,
                ]);
            });

            $this->createPrediction($fixture->id);

            $grounds = ['home', 'away'];

            // $fixtureResponse = collect($apiFootball::fixtures(['id' => $fixture->id])->object()->response ?? [])->first();
            // $fixtureResponse = $value;

            foreach ($grounds as $ground) {
                $team = Team::whereId($value->teams->{$ground}->id)->firstOr(function () use ($apiFootball, $value, $ground) {
                    unset($params);
                    $params['id'] = $value->teams->{$ground}->id;

                    $response = collect($apiFootball::teams($params)->object()->response ?? [])->first();

                    return Team::forceCreate([
                        'id' => $response->team->id,
                        'name' => $response->team->name,
                        'code' => $response->team->code??$response->team->name,
                        'national' => $response->team->national,
                        'logo' => $response->team->logo,
                        'flag' => $response->team->logo,
                    ]);
                });

                $fixtureResponse = $value;
                switch ($fixtureResponse->fixture->status->short) {
                    case 'AET':
                        $fixture->teams()->wherePivot('ground', $ground)->whereId($team->id)->syncWithoutDetaching([
                            $team->id => [
                                'ground' => $ground,
                                'goals' => $fixtureResponse->score->extratime->{$ground},
                            ]
                        ]);
                        break;

                    case 'PEN':
                        $fixture->teams()->wherePivot('ground', $ground)->whereId($team->id)->syncWithoutDetaching([
                            $team->id => [
                                'ground' => $ground,
                                'goals' => $fixtureResponse->score->penalty->{$ground},
                            ]
                        ]);
                        break;

                    default:
                        $fixture->teams()->wherePivot('ground', $ground)->whereId($team->id)->syncWithoutDetaching([
                            $team->id => [
                                'ground' => $ground,
                                'goals' => $fixtureResponse->goals->{$ground},
                            ]
                        ]);
                        break;
                }

//                if (optional($fixtureResponse)->score) {
//                    foreach ($fixtureResponse->score as $key => $score) {
//                        if (optional($score)->{$ground}/* && !is_null($score->{$ground})*/) {
//                            $fixture->teams()->wherePivot('ground', $ground)->whereId($team->id)->syncWithoutDetaching([
//                                $team->id => [
//                                    'ground' => $ground,
//                                    'goals' => is_null($score->{$ground}) ? 0 : $score->{$ground},
//                                ]
//                            ]);
//                        }
//                    }
//                }else {
//                    $fixture->teams()->wherePivot('ground', $ground)->whereId($team->id)->syncWithoutDetaching([
//                        $team->id => [
//                            'ground' => $ground,
//                            'goals' => is_null($value->goals->{$ground}) ? 0 : $value->goals->{$ground},
//                        ]
//                    ]);
//                }

//                if(! is_null($fixture->finished_at)){
//                    foreach ($fixture->predictions()->get() as $prediction){
//                        PredictionService::storePoints($prediction);
//                    }
//                }
            }
        }
    }

    public function refresh($option)
    {
        return self::{$option}();
    }

    private function status()
    {
        $apiFootball = new \ApiFootball();

        // matches are below current time/not finished yet ...
        $fixtures = Fixture::whereDate('timestamp', Carbon::now())
            ->where('timestamp', '<=', Carbon::now())
            ->whereNotIn('short_status', $apiFootball::fixtureFinishedStates())
            ->whereNull('finished_at')
            ->get();

        foreach ($fixtures as $fixture) {
            $param = ['id' => $fixture->id];
            $response = collect($apiFootball::fixtures($param)->object()->response ?? [])->first();

//            if (optional($response)->goals) {
//                $grounds = ['home', 'away'];
//                foreach ($grounds as $ground) {
//                    $team = $fixture->teams()->wherePivot('ground', $ground)->first();
//                    $team->pivot->goals = $response->goals->{$ground};
//                    $team->pivot->update();
//                }
//            }

//            if (optional($response)->score) {
//                foreach ($response->score as $key => $value) {
//                    if (optional($value)->home /*&& !is_null($value->home)*/ && optional($value)->away /*&& !is_null($value->away)*/) {
//                        $grounds = ['home', 'away'];
//                        foreach ($grounds as $ground) {
//                            $team = $fixture->teams()->wherePivot('ground', $ground)->first();
//                            $team->pivot->goals = is_null($value->{$ground}) ? 0 : $value->{$ground};
//                            $team->pivot->update();
//                        }
//                    }
//                }
//            }

            $grounds = ['home', 'away'];
            foreach ($grounds as $ground) {
                $team = $fixture->teams()->wherePivot('ground', $ground)->first();
                switch ($response->fixture->status->short) {
                    case 'AET':
                        $team->pivot->goals = $response->score->extratime->{$ground};
                        break;

                    case 'PEN':
                        $team->pivot->goals = $response->score->penalty->{$ground};
                        break;

                    default:
                        $team->pivot->goals = $response->goals->{$ground};
                        break;
                }
                $team->pivot->update();
            }


            if (optional($response)->fixture) {
                $fixture->long_status = $response->fixture->status->long;
                $fixture->short_status = $response->fixture->status->short;
                if (in_array($fixture->short_status, $apiFootball::fixtureFinishedStates()) && $fixture->isDirty('short_status')) {
                    $fixture->finished_at = Carbon::now();
                }
                $fixture->save();

//                if(! is_null($fixture->finished_at)){
//                    foreach ($fixture->predictions()->get() as $prediction){
//                        PredictionService::storePoints($prediction);
//                    }
//                }
            }
        }
        \Log::info('api-football:fixtures --refresh=status');
    }

    private
    function status2()
    {
        $apiFootball = new \ApiFootball();

        // matches are below current time/not finished yet ...
        $fixtures = Fixture::where('timestamp', '<', Carbon::now())
            ->whereIn('short_status', $apiFootball::fixtureTimestampStates())
            ->get();

        $this->update($fixtures);
    }

    private
    function timestamp()
    {
        // future matches
        $fixtures = Fixture::where('timestamp', '>', Carbon::now())
            ->get();
        $this->update($fixtures);
    }

    private
    function timestamp2()
    {
        $apiFootball = new \ApiFootball();
        // today's matches which has not finished/postponed/canceled etc
        $fixtures = Fixture::whereDate('timestamp', Carbon::now())
            ->whereIn('short_status', $apiFootball::fixtureTimestampStates())
            ->get();
        $this->update($fixtures);
    }

    private
    function update($fixtures)
    {

        $apiFootball = new \ApiFootball();

        foreach ($fixtures as $fixture) {
            $param = ['id' => $fixture->id];
            $response = collect($apiFootball::fixtures($param)->object()->response ?? [])->first();

            if (optional($response)->fixture) {
                $fixture->long_status = $response->fixture->status->long;
                $fixture->short_status = $response->fixture->status->short;
                $fixture->timezone = $response->fixture->timezone;
                $fixture->timestamp = Carbon::parse($response->fixture->date);
                $fixture->save();
            }
        }
        \Log::info('api-football:fixtures --refresh=timestamp');
    }

    public
    function restore()
    {
        DB::connection('api-football')->statement('SET FOREIGN_KEY_CHECKS=0');
        DB::connection('api-football')->table('fixtures')->truncate();
        DB::connection('api-football')->table('fixture_team')->truncate();
        DB::connection('api-football')->table('fixture_prediction_statistics')->truncate();
        DB::connection('api-football')->statement('SET FOREIGN_KEY_CHECKS=1');

        self::init();
    }

    public
    static function comparison(Fixture $fixture)
    {
        $predictions = $fixture->predictions();
        $count = $predictions->count();
        return [
            'comparison' => [
                'win' => [
                    'home' => (int)($count ? (($fixture->predictions()->whereColumn('home_team_goals', '>', 'away_team_goals')->count() / $count) * 100) : 0),
                    'away' => (int)($count ? (($fixture->predictions()->whereColumn('home_team_goals', '<', 'away_team_goals')->count() / $count) * 100) : 0),
                    'tie' => (int)($count ? (($fixture->predictions()->whereColumn('home_team_goals', '=', 'away_team_goals')->count() / $count) * 100) : 0),
                ],
            ]
        ];
    }

    public
    function position()
    {
        $apiFootball = new \ApiFootball();

        $fixtures = Fixture::whereDate('finished_at', Carbon::now())
            ->whereIn('short_status', $apiFootball::fixtureFinishedStates())
            ->whereNotNull('finished_at')
            ->get()
            ->filter(function ($fixture) {
                if (Carbon::now()->diffInMinutes(Carbon::parse($fixture->finished_at), false) == -2) {
                    return true;
                }
            });

        foreach ($fixtures as $fixture) {
            if (!is_null($fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals ?? null) &&
                !is_null($fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals ?? null)) {
                foreach ($fixture->predictions()->get() as $prediction) {
                    $prediction->world_position = RankService::getUsers($prediction->league, $prediction->season)->firstWhere('user.id', $prediction->user->id)['position'] ?? null;
                    $prediction->continent_position = RankService::getUsers($prediction->league, $prediction->season, 'continent', $prediction->user->country->continent)->firstWhere('user.id', $prediction->user->id)['position'] ?? null;
                    $prediction->country_position = RankService::getUsers($prediction->league, $prediction->season, 'country', $prediction->user->country)->firstWhere('user.id', $prediction->user->id)['position'] ?? null;
                    $prediction->update();
                }
            }
        }
    }

    public static function getUpcomingFixtures(Round $round) {
        if($round){
            $upcomingFixtures = Fixture::whereBelongsTo($round)->whereNull('finished_at')->where('timestamp', '>', carbon::now())->orderBy('timestamp')->take(2)->get();
            return $upcomingFixtures;
        }

        return [];
    }

    private function createVenue($venue) {
        if(!$venue->id){
            $apiFootball = new \ApiFootball();
            foreach (['name', 'city'] as $key => $value) {
                $params = [ $value => $venue->{$value} ];
                $response = $apiFootball::venues($params)->object()->response ?? [];
                if(count($response)) {
                    $venue->id = $response[0]->id;
                    break;
                }
            }
            $venue->id =  $venue->id ?? Venue::first()->id;
        }

        Venue::whereId($venue->id)->firstOr(function () use ($venue) {
            return Venue::forceCreate([
                'id' => $venue->id,
                'name' => $venue->name,
                'city' => $venue->city
            ]);
        });

        return $venue->id;
    }

    private function createPrediction($fixture_id) {
        $apiFootball = new \ApiFootball();

        $params = [ 'fixture' => $fixture_id ];
        $response = $apiFootball::predictions($params)->object()->response ?? [];

        foreach ($response as $key => $value) {
            return FixturePredictionStatistics::forceCreate([
                'fixture_id' => $fixture_id,
                'home' => $value->predictions->percent->home,
                'draw' => $value->predictions->percent->draw,
                'away' => $value->predictions->percent->away,
                'advice' => $value->predictions->advice
            ]);
        }
    }

    public function updatePrediction() {
        $fixtures = Fixture::where('league_id', 2)->get();

        foreach ($fixtures as $key => $fixture) 
        {
            $predictions = Prediction::where('fixture_id', $fixture->id)->get();
            foreach ($predictions as $key => $prediction) {
                $prediction->round_id = $fixture->round_id;
                $prediction->update();
            }
        }
        
    }
}
