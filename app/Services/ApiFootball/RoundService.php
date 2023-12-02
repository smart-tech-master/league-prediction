<?php

namespace App\Services\ApiFootball;

use App\Models\ApiFootball\Fixture;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Round;
use App\Models\ApiFootball\Season;
use App\Models\PostMatchPositioning;
use App\Models\User;
use App\Services\PostMatchPositioningService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;

class RoundService
{
    public function init()
    {
        $apiFootball = new \ApiFootball();
        
        $this->truncate();

        foreach (League::all() as $key => $league) {
            if($league->id == 2)
            {
                (new UEFAChampionsLeagueService())->init();
                continue;
            }

            $params = [
                'league' => $league->id,
                'season' => Season::first()->year
            ];

            $response = $apiFootball::rounds($params)->object()->response ?? [];

            $this->create($league, Season::first(), $response);
        }
    }

    public function create(League $league, Season $season, $response) {
        foreach ($response as $key => $value) {
            // if($this->filterByName($league, $value)) {
            //     continue;
            // }

            Round::forceCreate([
                'name' => $this->getRoundName($value),
                'alias' => $value,
                'slug' => $value,
                // 'keywords' => $this->getKeywords(['league' => $league->id, 'season' => $season->year, 'round' => $value]),
                'keywords' => [],
                'sl' => $key + 1,
                'league_id' => $league->id,
                'season_id' => $season->id
            ]);
        }
    }

    public function getKeywords($params) {
        $keywords = [];
        
        $apiFootball = new \ApiFootball();

        $response = $apiFootball::fixtures($params)->object()->response ?? [];

        foreach ($response as $key => $value) {
            $keywords[] = $value->fixture->id;
        }

        return $keywords;
    }

    public function points(User $user, Round $round)
    {
        return $user->predictions()->select('points')
            ->whereBelongsTo($round->league)->whereBelongsTo($round->season)->whereBelongsTo($round)
            ->sum('points');
    }

    public static function getCurrentRound(League $league, Season $season)
    {
        $currentRound = Round::whereBelongsTo($league)->whereBelongsTo($season)->where('current', true)->first();
        if($currentRound){
            return $currentRound;
        }

        $apiFootball = new \ApiFootball();
        return Round::whereBelongsTo($league)->whereBelongsTo($season)->get()
            ->filter(function ($round) use ($league, $season, $apiFootball) {
                if (is_null($round->started_at) && is_null($round->ended_at)) {
                    return false;
                }
                return Carbon::now()->between($round->started_at, $round->ended_at) ||
                    (Fixture::whereBelongsTo($league)->whereBelongsTo($season)->whereIn('short_status', $apiFootball::fixtureFinishedStates())->whereNotNull('finished_at')->orderByDesc('finished_at')->first()->round->id ?? null) == $round->id;
            })
            ->first();
    }

    public function getCurrentRoundFromApiFootball(League $league, Season $season){
        $apiFootball = new \ApiFootball();
        $params = [
            'league' => $league->id,
            'season' => $season->year,
            'current' => 'true'
        ];

        $response = $apiFootball::rounds($params)->object()->response ?? [];
        if(isset($response[0])){
            foreach (Round::whereBelongsTo($league)->whereBelongsTo($season)->get() as $round){
                $round->current = false;
                if(in_array($response[0], $round->keywords) || $response[0] == $round->slug){
                    $round->current = true;
                }
                $round->update();
            }
        }
    }

    public function highestPoints(User $user, Round $round)
    {
        return $user->predictions()
            ->whereBelongsTo($round->league)->whereBelongsTo($round->season)->whereBelongsTo($round)
            ->get()
            ->sortByDesc('points')
            ->first();
    }

    public function processEndedRound(Fixture $fixture)
    {
        $totalMatchesOnThatRound = $fixture->round->fixtures()->count();
        $totalPlayedMatchesOnThatRound = $fixture->round->fixtures()->whereNotNull('finished_at')->count();
        if ($totalMatchesOnThatRound == $totalPlayedMatchesOnThatRound) {
            PostMatchPositioning::whereBelongsTo($fixture->league)->whereBelongsTo($fixture->season)->orderByDesc('total_points')->chunk(100, function ($postMatchPositionings) use ($fixture) {
                foreach ($postMatchPositionings as $postMatchPositioning) {
                    $postMatchPositioning->total_points_on_last_round = (new RoundService())->points($postMatchPositioning->user, $fixture->round);
                    $postMatchPositioning->update();
                }
            });
        }
    }

    public function truncate() {
        DB::connection('api-football')->statement('SET FOREIGN_KEY_CHECKS=0');
        DB::connection('api-football')->table('rounds')->truncate();
        DB::connection('api-football')->statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function getRoundName($name) {
        // $exceptionPattern = "/semi|quarter|final/i";
        // $numberPattern = "/\d+/";

        // if(!preg_match($exceptionPattern, $name) && preg_match($numberPattern, $name, $match)) {
        //     if($match){
        //         $name = $match[0];
        //     }
        // }

        return $name;
    }

    public function filterByName(League $league, $name) {
        return $league->id == 2 && in_array($name, [
            "Preliminary Round",
            "1st Qualifying Round",
            "2nd Qualifying Round",
            "3rd Qualifying Round",
            "Play-offs"
        ]);
    }
}
