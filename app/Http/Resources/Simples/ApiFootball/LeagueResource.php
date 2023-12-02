<?php

namespace App\Http\Resources\Simples\ApiFootball;

use App\Http\Resources\ApiFootball\Rounds\SimpleResource;
use App\Http\Resources\ApiFootball\SimpleLeagueResource;
use App\Models\ApiFootball\Season;
use App\Services\ApiFootball\RoundService;
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

        $response = [
            $this->merge(function () {
                return SimpleLeagueResource::make($this);
            }),
            'current_round' => $currentRound ? SimpleResource::make($currentRound) : null,
        ];

        if ($request->user('sanctum')) {
            $response = array_merge($response, [
                'is_subscribed' => $request->user('sanctum')->leagues()->wherePivot('season_id', $currentSeason->id)->whereId($this->id)->first() ? true : false,
            ]);
        }

        return $response;
    }
}
