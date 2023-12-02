<?php

namespace App\Http\Resources\ApiFootball;

use Illuminate\Http\Resources\Json\JsonResource;

class FixturePredictionStatisticsResource extends JsonResource
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
          'home' => $this->home,
          'draw' => $this->draw,
          'away' => $this->away  
        ];
    }
}
