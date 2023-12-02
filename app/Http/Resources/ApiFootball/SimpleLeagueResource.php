<?php

namespace App\Http\Resources\ApiFootball;

use App\Http\Resources\ApiFootball\Rounds\SimpleResource;
use App\Models\ApiFootball\Season;
use App\Services\ApiFootball\RoundService;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleLeagueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'logo' => $this->logo,
        ];
    }
}
