<?php

namespace App\Http\Resources\ApiFootball;

use App\Models\ApiFootball\Fixture;
use App\Models\ApiFootball\Standing;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fixture = Fixture::findOrFail($this->pivot->fixture_id);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'logo' => $this->logo,
            'flag' => $this->flag,
            'ground' => $this->whenPivotLoaded('fixture_team', function (){
                return $this->pivot->ground;
            }),
            $this->mergeWhen(filled($this->pivot->goals), [
                'goals' => $this->pivot->goals
            ]),
            'standings' => [
                'rank' => Standing::whereBelongsTo($fixture->league)->whereBelongsTo($fixture->season)->whereBelongsTo($this->resource)->first()->rank ?? null,
            ]
        ];
    }
}
