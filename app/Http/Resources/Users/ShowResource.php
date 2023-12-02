<?php

namespace App\Http\Resources\Users;

use App\Models\ApiFootball\Season;
use App\Services\Paginations\Ranks\LeagueService;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowResource extends JsonResource
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
            $this->merge(function () {
                return IndexResource::make($this);
            }),
            'rank' => LeagueService::index($this->resource, Season::first())
        ];
    }
}
