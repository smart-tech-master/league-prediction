<?php

namespace App\Http\Resources\Paginations\Users;

use App\Http\Resources\ApiFootball\LeagueResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\Users\SimpleResource;
use App\Models\ApiFootball\Season;
use App\Services\RankService;
use App\Models\User;
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
            $this->merge(function (){
                return SimpleResource::make($this);
            }),
            'full_name' => $this->full_name,
            $this->mergeWhen($this->country, [
                'country' => CountryResource::make($this->country),
            ]),
            'bio' => utf8_decode($this->bio),
            'likes' => $this->likings()->whereType('like')->count(),
            'is_liked' => $this->likings()->whereType('like')->whereUserId($request->user('sanctum')->id)->first() ? true : false,
            $this->mergeWhen($request->user('sanctum') && $this->likings()->whereType('like')->whereUserId($request->user('sanctum')->id ?? 0)->first(), [
                'liked_at' => $this->likings()->whereType('like')->whereUserId($request->user('sanctum')->id ?? 0)->first()->created_at ?? null,
            ]),
            //'leagues_count' => $this->leagues()->wherePivot('season_id', Season::first()->id)->count(),
            // 'leagues_count' => $this->competitions()->count(),
            'leagues_count' => count(RankService::userPredictedLeagues(User::find($this->id))),
            'private_leagues_count' => $this->createdCompetitions()
                ->get()
                ->filter(function ($competitions){
                    return $competitions->category == 'league';
                })
                ->count(),
            'private_cups_count' => $this->createdCompetitions()
                ->get()
                ->filter(function ($competitions){
                    return $competitions->category == 'cup';
                })
                ->count(),
            // 'rank' => RankService::rank(User::find($this->id), null, Season::first()),
            'rank' => RankService::profileRank(User::find($this->id), null, Season::first()),
            'created_at' => $this->created_at,
        ];
    }
}
