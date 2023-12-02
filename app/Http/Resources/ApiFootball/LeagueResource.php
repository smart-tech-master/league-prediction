<?php

namespace App\Http\Resources\ApiFootball;

use App\Http\Resources\ApiFootball\Rounds\SimpleResource;
use App\Http\Resources\ApiFootball\FixtureResource;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Models\User;
use App\Models\Ad;
use App\Services\ApiFootball\LeagueService;
use App\Services\ApiFootball\RoundService;
use App\Services\ApiFootball\FixtureService;
use App\Http\Resources\AdResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LeagueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $currentSeason = Season::first();
        $currentRound = RoundService::getCurrentRound($this->resource, $currentSeason);
        $upcomingMatches = $currentRound ? FixtureService::getUpcomingFixtures($currentRound) : [];

        $response = [
            $this->merge(function () {
                return SimpleLeagueResource::make($this);
            }),
            'current_round' => $currentRound ? SimpleResource::make($currentRound) : null,
            'upcoming_matches' => count($upcomingMatches) ? FixtureResource::collection($upcomingMatches): null,
        ];

        if ($request->user('sanctum')) {
            $ads_logos = Ad::whereBelongsTo($request->user('sanctum')->country)->where('type', 'logo')->where('league_id', $this->id)->get() ?? [];
            $response = array_merge($response, [
                'ads_logos' => AdResource::collection($ads_logos),
            ]);

            // $ads_banner = Ad::whereBelongsTo($request->user('sanctum')->country)->where('type', 'banner')->get() ?? [];
            $ads_banner = [];

            $response = array_merge($response, [
                'ads_banner' => $ads_banner,
            ]);

            $response = array_merge($response, [
                'is_subscribed' => $request->user('sanctum')->leagues()->wherePivot('season_id', $currentSeason->id)->whereId($this->id)->first() ? true : false,
            ]);

            $response = array_merge($response, [
                'points' => (int)(new LeagueService)->points($request->user('sanctum'), League::find($this->id), $currentSeason),
            ]);
        }

        return $response;
    }
}
