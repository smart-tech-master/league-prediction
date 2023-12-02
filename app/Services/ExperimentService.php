<?php

namespace App\Services;

use App\Jobs\PostMatchPositioning\ProcessEndedRound;
use App\Jobs\PostMatchPositioning\ProcessFixture;
use App\Jobs\PostMatchPositioning\ProcessPredictionPoints;
use App\Jobs\PostMatchPositioning\ProcessSubscription;
use App\Models\ApiFootball\Fixture;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Round;
use App\Models\ApiFootball\Season;
use App\Models\Country;
use App\Models\CustomFootball\Competition;
use App\Models\Locale;
use App\Models\Prediction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;
use App\Models\PostMatchPositioning;

class ExperimentService
{
//experiments/createUsers?number=1000
//experiments/createPredictions?league=39&from=2&to=1001
//-- create competition --
//experiments/joinCompetitions?from=2&to=10001&ids[0]=1&ids[1]=2&ids[2]=3&ids[3]=4&ids[4]=5&ids[5]=6&ids[6]=7&ids[7]=8&ids[8]=9&ids[9]=10&ids[10]=11&ids[11]=12&ids[12]=13&ids[13]=14&ids[14]=15&ids[15]=16&ids[16]=17
//experiments/updateFixtures?league=39&time=2022-09-22 15:00
//experiments/calculatePoints?league=39
//experiments/showRounds?league=39

    public function createUsers(Request $request)
    {
        set_time_limit(36000); // 10hours

        for ($i = 1; $i <= $request->number; $i++) {
            $user = User::forceCreate([
                'username' => 'public-user' . $i,
                'full_name' => 'public user' . $i,
                'email' => 'developer.chayansarker+public-user' . $i . '@gmail.com',
                'country_id' => Country::inRandomOrder()->first()->id,
                'password' => Hash::make(123456),
                'dob' => Carbon::today()->subDays(rand(0, 365)),
                'role' => 'public-user',
                'bio' => 'bio of public-user.' . $i,
                'locale_id' => Locale::inRandomOrder()->first()->id,
                'receive_notifications' => true,
            ]);
        }
    }

    public function subscribeLeague(Request $request){
        set_time_limit(36000); // 10hours
        User::whereBetween('id', [$request->from, $request->to])->chunk(100, function ($users) use ($request){
            $league = League::findOrFail($request->league);
            $season = Season::first();
            foreach ($users as $user) {
                $user->leagues()
                    ->wherePivot('season_id', $season->id)
                    ->syncWithoutDetaching([$league->id => ['season_id' => $season->id]]);
                (new SubscriptionService())->createPostMatchPositioning($user, $league, $season);
                echo $user->id . '<br>';
            }
        });
    }

    public function createPredictions(Request $request)
    {
        set_time_limit(36000); // 10hours

        $users = User::whereBetween('id', [$request->from, $request->to])->get();
        foreach ($users as $user) {
            $rounds = Round::whereBelongsTo(League::findOrFail($request->league))->whereBelongsTo(Season::first())->get();
            foreach ($rounds as $round) {

                $user->leagues()
                    ->wherePivot('season_id', $round->season->id)
                    ->syncWithoutDetaching([$round->league->id => ['season_id' => $round->season->id]]);

                $fixtures = $round->fixtures()->inRandomOrder()->take(rand(0, $round->fixtures()->count()))->get();

                $multiplyByTwo = true;

                foreach ($fixtures as $fixture) {
                    $prediction = Prediction::whereUserId($user->id)
                        ->whereLeagueId($fixture->league->id)
                        ->whereSeasonId($fixture->season->id)
                        ->whereRoundId($fixture->round->id)
                        ->whereFixtureId($fixture->id)
                        ->firstOr(function () use ($user, $fixture, $multiplyByTwo) {
                            return Prediction::forceCreate([
                                'user_id' => $user->id,
                                'league_id' => $fixture->league->id,
                                'season_id' => $fixture->season->id,
                                'round_id' => $fixture->round->id,
                                'fixture_id' => $fixture->id,
                                'home_team_goals' => rand(0, 10),
                                'away_team_goals' => rand(0, 10),
                                'multiply_by_two' => $multiplyByTwo,
                            ]);
                        });

                    $multiplyByTwo = false;
                }
            }
        }
    }

    public function createExperimentsPredictions()
    {
        $league = 39;
        set_time_limit(36000); // 10hours

        $users = User::publicUser()->get();
        foreach ($users as $user) {
            $rounds = Round::whereBelongsTo(League::findOrFail($league))->whereBelongsTo(Season::first())->get();
            foreach ($rounds as $round) {

//                $user->leagues()
//                    ->wherePivot('season_id', $round->season->id)
//                    ->syncWithoutDetaching([$round->league->id => ['season_id' => $round->season->id]]);
//                (new SubscriptionService())->createPostMatchPositioning($user, $round->league, $round->season);

                $fixtures = $round->fixtures()->inRandomOrder()->take(rand(0, $round->fixtures()->count()))->get();

                $multiplyByTwo = true;

                foreach ($fixtures as $fixture) {
                    $prediction = Prediction::whereUserId($user->id)
                        ->whereLeagueId($fixture->league->id)
                        ->whereSeasonId($fixture->season->id)
                        ->whereRoundId($fixture->round->id)
                        ->whereFixtureId($fixture->id)
                        ->firstOr(function () use ($user, $fixture, $multiplyByTwo) {
                            return Prediction::forceCreate([
                                'user_id' => $user->id,
                                'league_id' => $fixture->league->id,
                                'season_id' => $fixture->season->id,
                                'round_id' => $fixture->round->id,
                                'fixture_id' => $fixture->id,
                                'home_team_goals' => rand(0, 10),
                                'away_team_goals' => rand(0, 10),
                                'multiply_by_two' => $multiplyByTwo,
                            ]);
                        });

                    $multiplyByTwo = false;
                }
            }
            echo 'user-' . $user->id . '-\n<br>';
        }
    }

    public function joinCompetitions(Request $request)
    {
        set_time_limit(36000); // 10hours

        foreach ($request->ids as $id) {

            $competition = Competition::findOrFail($id);

            $users = User::whereBetween('id', [$request->from, $request->to])->inRandomOrder()->take($competition->participants == -1 ? (($request->to - $request->from)) : ($competition->participants - 1))->where('id', '!=', $competition->user->id)->get();

            foreach ($users as $user) {
//                $user->leagues()
//                    ->wherePivot('season_id', $competition->season->id)
//                    ->syncWithoutDetaching([$competition->league->id => ['season_id' => $competition->season->id]]);
//                (new SubscriptionService())->createPostMatchPositioning($user, $competition->league, $competition->season);
                $user->competitions()->syncWithoutDetaching([$competition->id => [
                    'created_at' => Carbon::parse(mt_rand(Carbon::parse($competition->created_at)->timestamp, Carbon::now()->timestamp)),
                ]]);
            }
        }
    }

    public function updateFixtures(Request $request)
    {
        set_time_limit(36000); // 10hours
        //echo '<pre>';print_r(Carbon::parse($request->time)->format('Y-m-d H:i:s'));exit;
        $league = League::findOrFail($request->league);
        $rounds = Round::whereBelongsTo($league)->whereBelongsTo(Season::first())->get();
        $time = Carbon::parse($request->time)->format('Y-m-d H:i:s');
        $sl = 0;
        foreach ($rounds as $round) {
            foreach ($round->fixtures()->get() as $fixture) {
                $time = Carbon::parse($request->time)->addMinutes($sl * 1);
                $fixture->timestamp = $time;
                $fixture->finished_at = $request->type == 'init' ? null : $time->addMinute();
                $fixture->long_status = $request->type == 'init' ? 'Not Started' : 'Match Finished';
                $fixture->short_status = $request->type == 'init' ? 'NS' : 'FT';
                $fixture->update();

//                $grounds = ['home', 'away'];
//                foreach ($grounds as $ground) {
//                    $team = $fixture->teams()->wherePivot('ground', $ground)->first();
//                    $team->pivot->goals = $request->type == 'init' ? null : rand(0, 10);
//                    $team->pivot->update();
//                }
                $sl++;
            }
        }

//        foreach ($rounds as $round) {
//            foreach ($round->fixtures()->get() as $fixture) {
//                Prediction::whereBelongsTo($fixture)->chunk(500, function ($predictions){
//                    foreach ($predictions as $prediction){
//                        $prediction->setConnection('mysql')->points = PredictionService::calculatePoints($prediction);
//                        $prediction->setConnection('mysql')->save();
//                    }
//                });
//            }
//        }
    }

    public function calculatePoints(Request $request)
    {
        set_time_limit(36000); // 10hours
        $league = League::findOrFail($request->league);

        Prediction::whereBelongsTo($league)->whereBelongsTo(Season::first())->chunk(200, function ($predictions) {
            foreach ($predictions as $prediction) {
                $prediction->setConnection('mysql')->points = PredictionService::calculatePoints($prediction);
                $prediction->setConnection('mysql')->save();
            }
        });
    }

    public function showRounds(Request $request)
    {
        echo Carbon::now() . '<br>';
        foreach (Round::whereBelongsTo(League::findOrFail($request->league))->whereBelongsTo(Season::first())->get() as $round) {
            echo $round->id . ' - ' . $round->name . ' - ' . $round->started_at . ' - ' . $round->ended_at . '<br>';
        }
    }

    public function mytest(){
//        $fixture = Fixture::find(946943);
//        echo $fixture->round->fixtures()->orderByDesc('timestamp')->first()->id;
        foreach (User::publicUser()->get() as $user){
            foreach ($user->leagues()->get() as $league){
                    (new SubscriptionService())->createPostMatchPositioning($user, $league, Season::find($league->pivot->season_id));
                }
            }
        foreach (Fixture::whereNotNull('finished_at')->orderByDesc('finished_at')->get() as $fixture){
            ProcessPredictionPoints::dispatch($fixture);
            ProcessFixture::dispatch($fixture);
            ProcessEndedRound::dispatch($fixture);
        }
    }

    public function checkPoint(Request $request) {
        set_time_limit(36000);
        $totalPoint = $request->checkPoint;
        while($totalPoint <= $request->checkPoint){
            $user = User::where('id', $request->user_id)->first();
            $league = League::where('id', $request->league_id)->first();
            $records = PostMatchPositioning::whereBelongsTo($user)->whereBelongsTo($league);
            $totalPoint += $request->checkPoint;
        }

        return $totalPoint;
    }

    public function justDoIt()
    {
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0');
        DB::connection('mysql')->table('users')->truncate();
        DB::connection('mysql')->table('predictions')->truncate();
        DB::connection('mysql')->table('post_match_positionings')->truncate();
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1');
        return "success";
    }
}
