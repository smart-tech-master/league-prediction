<?php

namespace App\Services\CustomFootball;

use App\Http\Resources\Users\SimpleResource;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Round;
use App\Models\ApiFootball\Season;
use App\Models\Country;
use App\Models\CustomFootball\Competition;
use App\Models\CompetitionUser;
use App\Models\PostMatchPositioning;
use App\Models\User;
use App\Models\Prediction;
use App\Services\ApiFootball\RoundService;
use App\Services\RankService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CompetitionService
{

    public function competitionableLeagues()
    {
        return collect(League::all()->pluck('id'));
        // return collect([1, 2, 39]);
    }

    public function category(Competition $competition)
    {
        // return is_null($competition->type) && is_null($competition->round_id) ? 'league' : 'cup';
        return $competition->category;
    }

    public function subscribable(Competition $competition, User $user)
    {
        //echo '<pre>';print_r($competition);exit;
        // prediction subscription
        if (!in_array($competition->league->id, $user->leagues()->get()->pluck('id')->toArray())) {
            throw ValidationException::withMessages(['competition' => trans('validation.in', ['attribute' => 'competition'])]);
        }

        // not invalid league
        if (!in_array($competition->league->id, self::competitionableLeagues()->toArray())) {
            throw ValidationException::withMessages(['competition' => trans('validation.in', ['attribute' => 'competition'])]);
        }
        // not invalid season
        if ($competition->season->id != Season::first()->id) {
            throw ValidationException::withMessages(['competition' => trans('validation.in', ['attribute' => 'season'])]);
        }
        // check league/cup is not full
        if ($competition->participants >= 0 && $competition->competitors()->count() == $competition->participants &&
            !in_array($user->id, $competition->competitors()->get()->pluck('id')->values()->toArray())
        ) {
            throw ValidationException::withMessages(['competition' => __(ucfirst($competition->category) . ' is Full')]);
        }

        if ($competition->category == 'cup' && !is_null($competition->round->started_at) && Carbon::now()->diffInMinutes(Carbon::parse($competition->round->started_at), false) < 0) {
            throw ValidationException::withMessages(['competition' => trans('others.expired')]);
        }

        return true;
    }

    public static function roundPoints(User $user, League $league, Season $season)
    {
        $round = RoundService::getCurrentRound($league, $season);
        if ($round) {
            return (new RoundService())->points($user, $round);
        }

        return null;
    }

    public function code(Competition $competition)
    {
        return str_pad($competition->id, 10, "0", STR_PAD_LEFT);
        //return Hashids::connection('competition')->encode($competition->id);
    }

    public function validStartingRounds(Request $request)
    {
        $leauge = League::findOrFail($request->league);
        $currentRound = RoundService::getCurrentRound($leauge, Season::first());
        // return collect(Round::whereBelongsTo($leauge)->whereBelongsTo(Season::first())->where('sl', '>', $currentRound ? $currentRound->sl : 0)->get()->pluck('id')->toArray());
        return collect(Round::whereBelongsTo($leauge)->whereBelongsTo(Season::first())->where('sl', '>',  0)->get()->pluck('id')->toArray());
    }

    public static function getCupStatus(Competition $competition)
    {
        if ($competition->category == 'cup') {
            if ($competition->competitors()->count() < $competition->participants) {
                return 'inactive';
            } elseif ($competition->competitors()->count() == $competition->participants) {
                return 'full';
            }
        }
        return 'inactive';
    }

    public static function rankAmongOthers(User $user, Competition $competition)
    {
        if ($competition->category == 'league' && $competition->competitors()->find($user)) {

            $league = $competition->league;
            $season = $competition->season;

            return PostMatchPositioning::whereRaw('user_id in (' . $competition->competitors()->select('id')->get()->pluck('id')->implode(',') . ')')
                    ->whereBelongsTo($league)->whereBelongsTo($season)->orderByDesc('total_points')->orderBy('current_world_position')->get()->pluck('user_id')->flip()->get($user->id) + 1;
        }

        return 'N/A';
    }

    public static function out(User $user, Competition $competition)
    {
        if ($competition->category == 'cup' && $competition->competitors()->find($user->id)) {
            $round = $competition->rounds()->orderByDesc('id')->first();
            if($round) {
                if (in_array($user->id, $round->fixtures()->get()
                    ->map(function ($fixture) {
                        return [$fixture->users()->get()->first()->id, $fixture->users()->get()->last()->id];
                    })
                    ->collapse()
                    ->all()
                )) {
                    return false;
                }else{
                    return true;
                }
            }else{
                return false;
            }
        }
        return false;
    }

    public static function winner(User $user, Competition $competition)
    {
        if ($competition->category == 'league' && $competition->competitors()->find($user->id)) {
            $round = Round::whereBelongsTo($competition->league)->whereBelongsTo($competition->season)->get()->last();
            if ($round && $round
                    ->fixtures()->orderByDesc('timestamp')->first() && Carbon::now()->diffInMinutes($round
                    ->fixtures()->orderByDesc('timestamp')->first()->finished_at, false) < -1) {

                $league = $competition->league;
                $season = $competition->season;

                $postMatchPositioning = PostMatchPositioning::whereRaw('user_id in (' . $competition->competitors()->select('id')->get()->pluck('id')->implode(',') . ')')
                    ->whereBelongsTo($league)->whereBelongsTo($season)->orderByDesc('total_points')->first();

                if($postMatchPositioning){
                    return SimpleResource::make($postMatchPositioning->user)->resolve();
                }
            }
        } elseif ($competition->category == 'cup' && $competition->competitors()->find($user->id)) {
            $customFootballRound = $competition->rounds()->get()->filter(function ($customFootballRound) {
                return $customFootballRound->fixtures()->count() == 1;
            })
                ->first();

            if ($customFootballRound && $customFootballRound->fixtures()->first()) {
                $fixture = $customFootballRound->fixtures()->first();
                $user1 = $fixture->users()->get()[0];
                $user2 = $fixture->users()->get()[1];

                $points1 = $points2 = 0;

                $prediction1 = (new \App\Services\ApiFootball\RoundService())->highestPoints($user1, $customFootballRound->toRound);
                $prediction2 = (new \App\Services\ApiFootball\RoundService())->highestPoints($user2, $customFootballRound->toRound);

                if ($prediction1) {
                    $points1 += $prediction1->points();
                }
                if ($prediction2) {
                    $points2 += $prediction2->points();
                }

                if ($points1 > $points2) {
                    return SimpleResource::make($user1);
                } elseif ($points2 > $points1) {
                    return SimpleResource::make($user2);
                } else {
                    return SimpleResource::make($customFootballRound->competition->competitors()->whereIn('id', [$user1->id, $user2->id])->orderByPivot('created_at', 'asc')->first());
                }
            }
        }
        return null;
    }

    public static function competitionUsers($competition) {
        return CompetitionUser::where('competition_id', $competition)->get();
    }

    public static function groupPoints($league, $competition) {
        return (int) Prediction::whereBelongsTo($league)->whereIn('user_id', CompetitionUser::where('competition_id', $competition)->get('user_id'))->sum('points');
    }
}
