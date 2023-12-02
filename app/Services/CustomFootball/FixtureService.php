<?php

namespace App\Services\CustomFootball;

use App\Jobs\CustomFootball\Fixture\ProcessInit;
use App\Jobs\CustomFootball\Fixture\ProcessStatus;
use App\Models\ApiFootball\Season;
use App\Models\CustomFootball\Competition;
use App\Models\CustomFootball\Fixture;
use App\Models\CustomFootball\Round;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixtureService
{
    public function init()
    {
        set_time_limit(36000);
        //echo Carbon::now()->diffInMinutes(Carbon::parse('2022-10-04T16:45:00.000000Z'), false);exit;
        $competitions = Competition::get()
            ->filter(function ($competition) {
                return $competition->category == 'cup' && ($competition->competitors()->count() == $competition->participants) && (! is_null($competition->round->started_at) && Carbon::now()->diffInMinutes(Carbon::parse($competition->round->started_at), false) == -1);
            });
        \Log::debug($competitions);

        foreach ($competitions as $competition){
            ProcessInit::dispatch($competition);
        }

        \Log::info('custom-football:fixtures');
    }

    public function processInit(Competition $competition){
        // from and to is same(like one-match)
        $from = $to = $competition->round;

        // home and away contains 2 rounds. to means next round
        if ($competition->type == 'home-and-away') {
            $to = \App\Models\ApiFootball\Round::whereBelongsTo($competition->league)->whereBelongsTo($competition->season)->whereSl($from->sl + 1)->first();
        }

        // competitors(joining by)
        $competitors = $competition->competitors()->orderByPivot('created_at', 'asc')->get()->split($competition->participants / 2);

        $round = Round::whereBelongsTo($competition->league)->whereBelongsTo($competition->season)->whereBelongsTo($competition)->has('fixtures')->whereBelongsTo($from, 'fromRound')->whereBelongsTo($to, 'toRound')->firstOr(function () use ($competition, $from, $to, $competitors) {
            return DB::transaction(function () use ($competition, $from, $to, $competitors) {
                // create round
                $createdRound = Round::forceCreate([
                    'name' => $competition->participants,
                    'league_id' => $competition->league->id,
                    'season_id' => $competition->season->id,
                    'from' => $from->id,
                    'to' => $to->id ?? 0,
                    'competition_id' => $competition->id,
                ]);

                foreach ($competitors as $competitor) {
                    $fixture = Fixture::forceCreate([
                        'league_id' => $createdRound->league->id,
                        'season_id' => $createdRound->season->id,
                        'competition_id' => $createdRound->competition->id,
                        'round_id' => $createdRound->id,
                    ]);
                    $fixture->users()->attach($competitor[0]);
                    $fixture->users()->attach($competitor[1]);
                }

                return $createdRound;
            });
        });
    }

    public function refresh($option)
    {
        return self::{$option}();
    }

    public function status()
    {
        set_time_limit(36000);

        $apiFootball = new \ApiFootball();

        $apiFootballRounds = \App\Models\ApiFootball\Round::whereBelongsTo(Season::first())->get()
            ->filter(function ($apiFootballRound) use ($apiFootball) {
                if (!is_null($apiFootballRound->ended_at) && Carbon::now()->diffInDays(Carbon::parse($apiFootballRound->ended_at), false) == 0) {
                    $fixture = $apiFootballRound->fixtures()->whereTimestamp($apiFootballRound->ended_at)->whereNotNull('finished_at')->whereIn('short_status', $apiFootball::fixtureFinishedStates())->orderByDesc('finished_at')->first();
                    if ($fixture && Carbon::now()->diffInMinutes(Carbon::parse($fixture->finished_at), false) == -1) {
                        return true;
                    }
                }
            });

        Log::debug('api-football-rounds-' . $apiFootballRounds);

        foreach ($apiFootballRounds as $apiFootballRound){
            ProcessStatus::dispatch($apiFootballRound);
        }

        \Log::info('custom-football:fixtures --refresh=status');
    }

    public function processStatus(\App\Models\ApiFootball\Round $apiFootballRound){
        foreach ($apiFootballRound->competitionToRounds()->get() as $customFootballRound) {

            $customFootballRoundsToPlay = (new RoundService())->remainingRounds($customFootballRound->competition->type, $customFootballRound->competition->participants);

            $customFootballPlayedRounds = $customFootballRound->competition->rounds()->get()->pluck('from')->merge($customFootballRound->competition->rounds()->get()->pluck('to'))->unique()->count();

            $customFootballLastRound = ($customFootballRoundsToPlay - $customFootballPlayedRounds) == 1;

            if($customFootballRoundsToPlay == $customFootballPlayedRounds){}else {
                $from = $to = \App\Models\ApiFootball\Round::whereBelongsTo($customFootballRound->league)
                    ->whereBelongsTo($customFootballRound->season)->whereSl($customFootballRound->toRound->sl + 1)->first();

                if ($customFootballRound->competition->type == 'home-and-away' && !$customFootballLastRound) {
                    $to = \App\Models\ApiFootball\Round::whereBelongsTo($from->league)
                        ->whereBelongsTo($from->season)->whereSl($from->sl + 1)->first();
                }

                $competitors = collect();

                foreach ($customFootballRound->fixtures()->get() as $fixture) {
                    $user1 = $fixture->users()->get()[0];
                    $user2 = $fixture->users()->get()[1];

                    $points1 = $points2 = 0;

                    foreach (\App\Models\ApiFootball\Round::whereBetween('id', [$customFootballRound->fromRound->id, $customFootballRound->toRound->id])->get() as $apiFootballRound) {

                        $prediction1 = (new \App\Services\ApiFootball\RoundService())->highestPoints($user1, $apiFootballRound);
                        $prediction2 = (new \App\Services\ApiFootball\RoundService())->highestPoints($user2, $apiFootballRound);

                        if ($prediction1) {
                            $fixture->predictions()->attach($prediction1);
                            $points1 += $prediction1->points();
                        }
                        if ($prediction2) {
                            $fixture->predictions()->attach($prediction2);
                            $points2 += $prediction2->points();
                        }
                    }

                    if ($points1 > $points2) {
                        $competitors[] = $user1;
                    } elseif ($points2 > $points1) {
                        $competitors[] = $user2;
                    } else {
                        $competitors[] = $customFootballRound->competition->competitors()->whereIn('id', [$user1->id, $user2->id])->orderByPivot('created_at', 'asc')->first();
                    }
                }

                $cutomFootballRound = Round::whereBelongsTo($customFootballRound->league)->whereBelongsTo($customFootballRound->season)->whereBelongsTo($customFootballRound->competition)->has('fixtures')->whereBelongsTo($from, 'fromRound')->whereBelongsTo($to, 'toRound')->firstOr(function () use ($customFootballRound, $from, $to, $competitors) {
                    return DB::transaction(function () use ($customFootballRound, $from, $to, $competitors) {
                        // create round
                        $createdRound = Round::forceCreate([
                            'name' => $competitors->count() == 2 ? 'F' : $competitors->count(),
                            'league_id' => $customFootballRound->league->id,
                            'season_id' => $customFootballRound->season->id,
                            'from' => $from->id,
                            'to' => $to->id ?? 0,
                            'competition_id' => $customFootballRound->competition->id,
                        ]);

                        foreach (collect($competitors)->split($competitors->count() / 2) as $competitor) {
                            $fixture = Fixture::forceCreate([
                                'league_id' => $createdRound->league->id,
                                'season_id' => $createdRound->season->id,
                                'competition_id' => $createdRound->competition->id,
                                'round_id' => $createdRound->id,
                            ]);
                            $fixture->users()->attach($competitor[0]);
                            $fixture->users()->attach($competitor[1]);
                        }

                        return $createdRound;
                    });
                });
            }
        }
    }
}
