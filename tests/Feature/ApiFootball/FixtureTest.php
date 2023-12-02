<?php

namespace Tests\Feature\ApiFootball;

use App\Models\ApiFootball\Fixture;
use App\Models\ApiFootball\Round;
use App\Models\ApiFootball\Team;
use App\Models\Prediction;
use App\Services\PredictionService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FixtureTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

//    public function test_goals_after_match_end(){
//        $prediction = Prediction::findOrFail(241);
//        echo PredictionService::storePoints($prediction);exit;
//        //echo PredictionService::calculatePoints($prediction);exit;
//    }

//    public function test_goals_after_match_end()
//    {
//        $apiFootball = new \ApiFootball();
//        $fixture = Fixture::findOrFail(978072);
//        $prediction = Prediction::findOrFail(241);
//        echo PredictionService::calculatePoints($prediction);exit;
//
//        $param = ['id' => 868316];
//        $response = collect($apiFootball::fixtures($param)->object()->response ?? [])->first();
//        if (optional($response)->score) {
//            foreach ($response->score as $key => $value){
//                if(optional($value)->home && ! is_null($value->home) && optional($value)->away && ! is_null($value->away)){
//                    echo $key . ':home-' . $value->home . ',away-' . $value->away . '\n';
////                    $grounds = ['home', 'away'];
////                    foreach ($grounds as $ground) {
////                        $team = $fixture->teams()->wherePivot('ground', $ground)->first();
////                        $team->pivot->goals = $value->{$ground};
////                        $team->pivot->update();
////                    }
//                }
//            }
//        }
//        echo '<pre>';print_r($response->score);exit;
//
//        //$response->assertStatus(200);
//    }

    public function test_goals_after_match_end2()
    {
        $apiFootball = new \ApiFootball();
        $round = Round::findOrFail(39);

        $this->fixturesByKeywords($apiFootball, $round);

        $predictions = Prediction::whereBelongsTo($round)->get();
        foreach ($predictions as $prediction){
            PredictionService::storePoints($prediction);
        }
    }

//
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

    private function create($apiFootball, $round, $response)
    {

        foreach ($response as $value) {
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
                    'finished_at' => !is_null($value->fixture->status->elapsed) ? Carbon::parse($value->fixture->date)->addMinutes($value->fixture->status->elapsed) : null,
                ]);
            });

            $grounds = ['home', 'away'];

            $fixtureResponse = collect($apiFootball::fixtures(['id' => $fixture->id])->object()->response ?? [])->first();

            foreach ($grounds as $ground) {
                $team = Team::whereId($value->teams->{$ground}->id)->firstOr(function () use ($apiFootball, $value, $ground) {
                    unset($params);
                    $params['id'] = $value->teams->{$ground}->id;

                    $team = collect($apiFootball::teams($params)->object()->response ?? [])->first();

                    return Team::forceCreate([
                        'id' => $team->team->id,
                        'name' => $team->team->name,
                        'code' => $team->team->code,
                        'national' => $team->team->national,
                        'logo' => $team->team->logo,
                        'flag' => $team->team->logo,
                    ]);
                });

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
//                                    //'goals' => isset($score->{$ground}) ? (is_null($score->{$ground}) ? 0 : $score->{$ground}) : 0,
//                                    //'goals' => is_null($score->{$ground}) ? 0 : $score->{$ground},
//                                ]
//                            ]);
//                        }
//                    }
//                }else {
//                    echo 'goal-' . $ground . ':' . $value->goals->{$ground} . PHP_EOL;
//                    $fixture->teams()->wherePivot('ground', $ground)->whereId($team->id)->syncWithoutDetaching([
//                        $team->id => [
//                            'ground' => $ground,
//                            //'goals' => is_null($value->goals->{$ground}) ? 0 : $value->goals->{$ground},
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
}
