<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\ApiFootball\LeagueResource;
use App\Http\Resources\CountryResource;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
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
        return [
            $this->merge(function (){
                return SimpleResource::make($this);
            }),
            'full_name' => $this->full_name,
            $this->mergeWhen($this->country, [
                'country' => CountryResource::make($this->country),
            ]),
            'bio' => utf8_decode($this->bio),
            // $this->mergeWhen($this->leagues()->wherePivot('season_id', Season::first()->id)->count() > 0, [
            //     'leagues' => LeagueResource::collection($this->leagues()->wherePivot('season_id', Season::first()->id)->get()),
            // ]),
            $this->merge([
                'leagues' => LeagueResource::collection(League::all()),
            ]),
            'likes' => $this->likings()->whereType('like')->count(),
            $this->mergeWhen($request->user('sanctum') && $this->likings()->whereType('like')->whereUserId($request->user('sanctum')->id ?? 0)->first(), [
                'liked_at' => $this->likings()->whereType('like')->whereUserId($request->user('sanctum')->id ?? 0)->first()->created_at ?? null,
            ]),
            'competitions_count' => $this->competitions()->count(),
        ];
    }
}
