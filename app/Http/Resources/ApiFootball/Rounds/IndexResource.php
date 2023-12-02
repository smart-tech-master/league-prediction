<?php

namespace App\Http\Resources\ApiFootball\Rounds;

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
        $response = [
            $this->merge(function (){
                return SimpleResource::make($this);
            }),
            $this->mergeWhen(! is_null($this->started_at) && ! is_null($this->ended_at), [
                'started_at' => $this->started_at,
                'ended_at' => $this->ended_at,
            ]),
//            $this->mergeWhen($this->id == (RoundService::getCurrentRound($this->league, $this->season)->id ?? null), [
//                'current_round' => true,
//            ]),
        ];

        if($request->user('sanctum')){
            $response = array_merge($response, [
                'points' => (int) $this->points(),
            ]);
        }

        return $response;
    }
}
