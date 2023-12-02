<?php

namespace App\Http\Resources\CustomFootball\Competitions;

use App\Http\Resources\Users\CompetitorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowResource extends JsonResource
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
            $this->merge(function (){
                return IndexResource::make($this->resource);
            }),
//            $this->mergeWhen($this->category == 'league', [
//                'competitors' => CompetitorResource::collection($this->competitors()->get())->competition($this),
//            ]),
            $this->mergeWhen($this->category == 'cup', [
                'rounds' => \App\Http\Resources\CustomFootball\Rounds\IndexResource::collection($this->rounds()->get()),
            ]),
        ];
    }
}
