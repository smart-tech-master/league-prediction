<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PredictionResource extends JsonResource
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
                'home_team_goals' => $this->home_team_goals,
                'away_team_goals' => $this->away_team_goals,
                'multiply' => $this->multiply_by_two,
                //'points' => $this->points(),
                'points' => $this->points,
            ];
    }
}
