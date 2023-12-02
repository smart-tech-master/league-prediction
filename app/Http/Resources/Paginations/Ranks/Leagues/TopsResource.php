<?php

namespace App\Http\Resources\Paginations\Ranks\Leagues;

use App\Http\Resources\Users\SimpleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TopsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return [
        //     'position' => $this->{'current_' . $request->tops . '_position'},
        //     'most_recent_position' => $this->{'previous_' . $request->tops . '_position'},
        //     'user' => SimpleResource::make($this->user),
        //     'points_from_last_round' => $this->total_points_on_last_round == null ? 0 : $this->total_points_on_last_round,
        //     'points' => $this->total_points == null ? 0 : $this->total_points,
        // ];

        return [
            'user' => SimpleResource::make($this->user),
            'points' => $this->points  ?? 0 ,
            // 'points_from_last_round' => $this->total_points_on_last_round == null ? 0 : $this->total_points_on_last_round,
            'current_round_points' => $this->pointsFromLastRound,
            'points_from_last_round' => $this->pointsFromLastRound,
            'position' => $this->position,
        ];
    }
}
