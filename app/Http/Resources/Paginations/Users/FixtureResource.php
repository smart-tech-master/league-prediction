<?php

namespace App\Http\Resources\Paginations\Users;

use App\Http\Resources\ApiFootball\Rounds\IndexResource;
use App\Http\Resources\PredictionResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ApiFootball\SimpleLeagueResource;
use App\Http\Resources\ApiFootball\SeasonResource;
use App\Http\Resources\ApiFootball\TeamResource;
use App\Http\Resources\ApiFootball\FixturePredictionStatisticsResource;


class FixtureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $prediction = $this->user->predictions()->whereBelongsTo($this->league)->whereBelongsTo($this->season)->whereBelongsTo($this, 'fixture')->first();
            
        $response = [
            'id' => $this->id,
            'timezone' => $this->timezone,
            'timestamp' => $this->timestamp,
            'long_status' => $this->long_status,
            'short_status' => $this->short_status,
            'league' => SimpleLeagueResource::make($this->league),
            'season' => SeasonResource::make($this->season),
            'stadium' => $this->venue->name,
            'fixture_prediction_statistics' => FixturePredictionStatisticsResource::make($this->FixturePredictionStatistics),
            'teams' => TeamResource::collection($this->teams()->get()),
            $this->merge(function () {
                return $this->comparison();
            }),
            'prediction' => PredictionResource::make($prediction),
        ];

        return $response;
    }
}
