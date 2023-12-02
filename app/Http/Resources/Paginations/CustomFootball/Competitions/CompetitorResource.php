<?php

namespace App\Http\Resources\Paginations\CustomFootball\Competitions;

use App\Http\Resources\Users\SimpleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CompetitorResource extends JsonResource
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
            'position' => $this->current_world_position,
            'most_recent_position' => $this->previous_world_position,
            'user' => SimpleResource::make($this->user),
            'points_from_last_round' => $this->total_points_on_last_round == null ? 0 : $this->total_points_on_last_round,
            'current_round_points' => $this->total_points_on_last_round == null ? 0 : $this->total_points_on_last_round,
            'points' => $this->total_points == null ? 0 : $this->total_points,
        ];
    }
}
