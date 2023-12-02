<?php

namespace App\Http\Resources\ApiFootball;

use App\Http\Resources\ApiFootball\Rounds\IndexResource;
use App\Http\Resources\AdResource;
use App\Http\Resources\PredictionResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Ad;
use App\Services\AdService;

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
        $response = [
            'id' => $this->id,
            'timezone' => $this->timezone,
            'timestamp' => $this->timestamp,
            'long_status' => $this->long_status,
            'short_status' => $this->short_status,
            //'league_round' => $this->league_round,
            'league' => SimpleLeagueResource::make($this->league),
            'season' => SeasonResource::make($this->season),
            'stadium' => $this->venue->name,
            'fixture_prediction_statistics' => FixturePredictionStatisticsResource::make($this->FixturePredictionStatistics),
            //'round' => IndexResource::make($this->round),
            'teams' => TeamResource::collection($this->teams()->get()),
            //            $this->mergeWhen($request->user('sanctum'), function () {
                //                return $this->comparison();
                //            }),
                $this->merge(function () {
                    return $this->comparison();
                }),
            ];
            
        if ($request->user('sanctum')) {
            // $ads_among_matches = AdResource::collection(Ad::whereBelongsTo($request->user('sanctum')->country)->where('type', 'banner')->where('league_id', $this->league->id)->get() ?? []);
            $ads_among_matches = AdService::adsAmongMatches($this->league, $request->user('sanctum')->country);
            $response = array_merge($response, ['ads_among_matches' => $ads_among_matches ]);

            // $prediction = $request->user('sanctum')->predictions()->whereBelongsTo($this->league)->whereBelongsTo($this->season)->whereBelongsTo($this->round)->whereBelongsTo($this, 'fixture')->first();
            $prediction = $request->user('sanctum')->predictions()->whereBelongsTo($this->league)->whereBelongsTo($this->season)->whereBelongsTo($this, 'fixture')->first();
            
            if($prediction) {
                $response = array_merge($response, ['prediction' => PredictionResource::make($prediction)]);
            }
        }

        return $response;
    }
}
