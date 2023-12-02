<?php

namespace App\Http\Resources\Paginations\Ranks\Leagues;

use App\Http\Resources\ApiFootball\SeasonResource;
use App\Services\ApiFootball\RoundService;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $season = $request->route()->parameter('season');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'season' => SeasonResource::make($request->route()->parameter('season')),
            'current_round' => RoundResource::make(RoundService::getCurrentRound($this->resource, $season)),
        ];
    }
}
