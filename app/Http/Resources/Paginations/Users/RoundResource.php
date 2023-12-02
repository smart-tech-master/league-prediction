<?php

namespace App\Http\Resources\Paginations\Users;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\RankService;
use App\Models\ApiFootball\Round;
use App\Http\Resources\Users\SimpleResource;
use App\Http\Resources\ApiFootball\LeagueResource;


class RoundResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => (string)$this->sl,
            'points' => RankService::pointsByRound($this->user, $this->league, Round::find($this->id)),
            'user' => SimpleResource::make($this->user),
            'league' => LeagueResource::make($this->league),
        ];
    }
}
