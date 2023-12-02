<?php

namespace App\Services;

use App\Http\Resources\Account\ProfileResource;
use App\Jobs\PostMatchPositioning\ProcessSubscription;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Models\CustomFootball\Competition;
use App\Models\PostMatchPositioning;
use App\Models\User;
use App\Services\ApiFootball\RoundService;
use Illuminate\Http\Request;

class SubscriptionService
{
    public function store(Request $request)
    {
        switch ($request->type) {
            case 'league':
                $league = League::findOrFail($request->league);
                $season = Season::findOrFail($request->season);
                $request->user()->leagues()
                    ->wherePivot('season_id', $season->id)
                    ->syncWithoutDetaching([$league->id => ['season_id' => $season->id]]);
                $this->createPostMatchPositioning($request->user(), $league, $season);
                break;
            case 'competition':
                // $competition = Competition::findOrFail($request->competition);
                $competition = Competition::find($request->competition);

                // if (!$competition && $competition->subscribable()) {
                if ($competition) {
                    $request->user()->competitions()->syncWithoutDetaching([$competition->id]);
                    break;
                }
        }

        return ProfileResource::make($request->user())->additional(['message' => __('Data has been updated successfully.')]);
    }

    public function createPostMatchPositioning(User $user, League $league, Season $season)
    {
        ProcessSubscription::dispatch($user, $league, $season);
    }
}
