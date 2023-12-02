<?php

namespace App\Services;

use App\Models\ApiFootball\Fixture;
use App\Models\Prediction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PredictionService
{
    public function store(Request $request)
    {
        if ($request->filled('predictions')) {
            foreach ($request->predictions as $key => $values) {
                if (filled($values['goals']['home']) && filled($values['goals']['away'])) {

                    $fixture = Fixture::findOrFail($key);
                    
                    if (isset($values['multiply_by_two']) && filled($values['multiply_by_two']) && $values['multiply_by_two'] == 1) {

                        $prediction = $request->user()->predictions()->whereLeagueId($fixture->league->id)
                            ->whereSeasonId($fixture->season->id)
                            ->whereRoundId($fixture->round->id)
                            ->whereMultiplyByTwo(true)
                            ->first();

                        if ($prediction && $fixture->id != $prediction->fixture_id) {
                            throw ValidationException::withMessages(['predictions.' . $key . '.multiply_by_two' => __('You have already enabled it for this round.')]);
                        }
                    }
                    
                    if(Carbon::now()->diffInMinutes(Carbon::parse($fixture->timestamp)->timezone($fixture->timezone), false) <= 0){
                        print_r(['predictions.' . $key . '.goals.home' => __('Match has already been started.')]); exit;
                        // throw ValidationException::withMessages(['predictions.' . $key . '.goals.home' => __('Match has already been started.')]);
                    }
                }
            }

            DB::transaction(function () use ($request) {
                foreach ($request->predictions as $key => $values) {
                    if (filled($values['goals']['home']) && filled($values['goals']['away'])) {
                        $fixture = Fixture::findOrFail($key);
                        
                        $multiplyByTwo = (isset($values['multiply_by_two']) && filled($values['multiply_by_two']) && $values['multiply_by_two'] == 1) ? true : false;
                        
                        $prediction = Prediction::whereUserId($request->user()->id)
                            ->whereLeagueId($fixture->league->id)
                            ->whereSeasonId($fixture->season->id)
                            // ->whereRoundId($fixture->round->id)
                            ->whereFixtureId($fixture->id)
                            ->firstOr(function () use ($request, $values, $fixture, $multiplyByTwo) {
                                return Prediction::forceCreate([
                                    'user_id' => $request->user()->id,
                                    'league_id' => $fixture->league->id,
                                    'season_id' => $fixture->season->id,
                                    'round_id' => $fixture->round->id,
                                    'fixture_id' => $fixture->id,
                                    'home_team_goals' => $values['goals']['home'],
                                    'away_team_goals' => $values['goals']['away'],
                                    'multiply_by_two' => $multiplyByTwo,
                                ]);
                            });
                        
                        if (!$prediction->wasRecentlyCreated) {
                            $prediction->home_team_goals = $values['goals']['home'];
                            $prediction->away_team_goals = $values['goals']['away'];
                            $prediction->multiply_by_two = $multiplyByTwo;
                            $prediction->update();
                        }

                        $result[] = $prediction;
                    }
                }
            });
        }

        return $key;
    }

    public static function points(Prediction $prediction){
        return is_null($prediction->points) ? 0 : $prediction->points;
    }

    public static function storePoints(Prediction $prediction){
        $prediction->setConnection('mysql')->points = self::calculatePoints($prediction);
        $prediction->setConnection('mysql')->update();
    }

    public static function calculatePoints(Prediction $prediction)
    {
//        echo 'actual home team goals:' . $prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals . PHP_EOL;
//        echo 'actual away team goals:' . $prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals . PHP_EOL;
//        echo 'prediction home team goals:' . $prediction->home_team_goals . PHP_EOL;
//        echo 'prediction away team goals:' . $prediction->away_team_goals . PHP_EOL;
        $a = $b = $c = false;
        $total = 0;

        if (is_null($prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals) || is_null($prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals)) {
            return $total;
        }

        // a. If the user predicts the Winner and Loser correctly or tie correctly (+2 pts).

        if($prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals > $prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals){
            $fWinner = $prediction->fixture->teams()->wherePivot('ground', 'home')->first();
            $fLoser = $prediction->fixture->teams()->wherePivot('ground', 'away')->first();
        }elseif ($prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals < $prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals) {
            $fWinner = $prediction->fixture->teams()->wherePivot('ground', 'away')->first();
            $fLoser = $prediction->fixture->teams()->wherePivot('ground', 'home')->first();
        }else{
            $fTie = true;
        }

        if($prediction->home_team_goals > $prediction->away_team_goals){
            $pWinner = $prediction->fixture->teams()->wherePivot('ground', 'home')->first();
            $pLoser = $prediction->fixture->teams()->wherePivot('ground', 'away')->first();
        }elseif($prediction->home_team_goals < $prediction->away_team_goals){
            $pWinner = $prediction->fixture->teams()->wherePivot('ground', 'away')->first();
            $pLoser = $prediction->fixture->teams()->wherePivot('ground', 'home')->first();
        }else{
            $pTie = true;
        }

//        if ($prediction->home_team_goals > $prediction->away_team_goals) {
//            $pWinner = $prediction->fixture->teams()->wherePivot('ground', 'home')->first();
//            $pLoser = $prediction->fixture->teams()->wherePivot('ground', 'away')->first();
//        } elseif ($prediction->home_team_goals < $prediction->away_team_goals) {
//            $pWinner = $prediction->fixture->teams()->wherePivot('ground', 'away')->first();
//            $pLoser = $prediction->fixture->teams()->wherePivot('ground', 'home')->first();
//        } else {
//            $pTie = true;
//        }

        if (isset($fWinner)
            && isset($fLoser)
            && isset($pWinner)
            && isset($pLoser)
            /*&& $fWinner
            && $fLoser
            && !is_null($pWinner->pivot->goals)
            && !is_null($pLoser->pivot->goals)*/) {

//            if ($prediction->home_team_goals > $prediction->away_team_goals) {
//                $pWinner = $fWinner;
//                $pLoser = $fLoser;
//            } elseif ($prediction->home_team_goals < $prediction->away_team_goals) {
//                $pWinner = $fLoser;
//                $pLoser = $fWinner;
//            }
//
//            if ($pWinner->pivot->goals > $pLoser->pivot->goals) {
//                $fWinner = $pWinner;
//                $fLoser = $pLoser;
//            } elseif ($pWinner->pivot->goals < $pLoser->pivot->goals) {
//                $fWinner = $pLoser;
//                $fLoser = $pWinner;
//            }

            if (/*isset($fWinner) && isset($fLoser) && */($pWinner->id == $fWinner->id && $pLoser->id == $fLoser->id)) {
                $a = true;
                $total += 2;
            }
        } elseif (isset($fTie) && isset($pTie)) {
            /*if ($prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals == $prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals) {*/
                $a = true;
                $total += 2;
            /*}*/

//            if(($prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals == $prediction->home_team_goals) || ($prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals == $prediction->away_team_goals)){
//                $total += 1;
//            }
        }

//        if (isset($pWinner)
//            && isset($pLoser)
//            && $pWinner
//            && $pLoser
//            && !is_null($pWinner->pivot->goals)
//            && !is_null($pLoser->pivot->goals)) {
//
//            if ($pWinner->pivot->goals > $pLoser->pivot->goals) {
//                $fWinner = $pWinner;
//                $fLoser = $pLoser;
//            } elseif ($pWinner->pivot->goals < $pLoser->pivot->goals) {
//                $fWinner = $pLoser;
//                $fLoser = $pWinner;
//            }
//
//            if (isset($fWinner) && isset($fLoser) && ($pWinner->id == $fWinner->id || $pLoser->id == $fLoser->id)) {
//                $a = true;
//                $total += 2;
//            }
//        } elseif (isset($pTie) && $pTie) {
//            if ($prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals == $prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals) {
//                $a = true;
//                $total += 2;
//            }
//        }

        // b. If the user predicts the score (# of goals) for the winning team correctly (+1 pts).
        //Even if the real score exceeds 9 goals. Only if the user predicts the winning score is
        //9 goals and the team scores more than 9 goals then this user will win the points.

        if (($prediction->home_team_goals == $prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals) || ($prediction->home_team_goals >= 9 && $prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals >= 9)) {
            $b = true;
            $total += 1;
        }

//        if ($prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals > $prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals) {
//            if ($prediction->home_team_goals == $prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals || ($prediction->home_team_goals == 9 && $prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals >= 9)) {
//                $b = true;
//                $total += 1;
//            }
//        } elseif ($prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals < $prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals) {
//            if ($prediction->away_team_goals == $prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals || ($prediction->away_team_goals == 9 && $prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals >= 9)) {
//                $b = true;
//                $total += 1;
//            }
//        }

        // c. If the user predicts the score (# of goals) for the losing team correctly (+1 pts)

        if (($prediction->away_team_goals == $prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals) || ($prediction->away_team_goals >= 9 && $prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals >= 9)) {
            $c = true;
            $total += 1;
        }

//        if (($prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals > $prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals)) {
//            if ($prediction->away_team_goals == $prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals) {
//                $c = true;
//                $total += 1;
//            }
//        } elseif (($prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals < $prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals)) {
//            if ($prediction->home_team_goals == $prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals) {
//                $c = true;
//                $total += 1;
//            }
//        }
        //echo 'isset:' . isset($pTie);
        // if match ends with a tie, any of goal is correct +1point
//        if (isset($pTie) && $pTie) { echo 'pTie';
//            if(($prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals == $prediction->home_team_goals) || ($prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals == $prediction->away_team_goals)){
//                $total += 1;
//            }
//        }

//        if(isset($fTie)){
//            if(($prediction->fixture->teams()->wherePivot('ground', 'home')->first()->pivot->goals == $prediction->home_team_goals) || ($prediction->fixture->teams()->wherePivot('ground', 'away')->first()->pivot->goals == $prediction->away_team_goals)){
//                $total += 1;
//            }
//        }


        // If all (a, b, and c) are correct (+1 pts).

        if ($a === true && $b === true && $c === true) {
            $total += 1;
        }

        if($prediction->multiply_by_two){
            $total *= 3;
        }

        return $total;
    }
}
