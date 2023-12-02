<?php

namespace App\Http\Resources\CustomFootball\Competitions;

use App\Http\Resources\ApiFootball\LeagueResource;
use App\Http\Resources\ApiFootball\SeasonResource;
use App\Http\Resources\ApiFootball\SimpleLeagueResource;
use App\Http\Resources\Users\SimpleResource;
use App\Models\Country;
use App\Http\Resources\CountryResource;
use App\Services\CustomFootball\RoundService;
use App\Services\CustomFootball\CompetitionService;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $currentRound = RoundService::getCurrentRound($this->league, $this->season);
        $lastRound = RoundService::getLastRound($this->league, $this->season);
        $countries = $this->country_id ? CountryResource::collection(Country::whereIn('id', json_decode($this->country_id))->get()) : [];

        return [
            'id' => $this->id,
            'code' => $this->code,
            'league' => SimpleLeagueResource::make($this->league),
            'season' => SeasonResource::make($this->season),
            'current_round' => $currentRound ? $currentRound->sl : 0,
            'last_round' => $lastRound ? $lastRound->sl : 0,
            'category' => $this->category,
            'title' => $this->title,
            'description' => $this->description,
            'joined_by' => $this->joined_by,
            'play_for' => $this->play_for,
            'logo' => $this->logo,
            'countries' => $countries,
            'category' => $this->category,
            $this->mergeWhen($this->play_for == 'prize', [
                'contact' => $this->contact,
            ]),
            $this->mergeWhen($this->category == 'league', [
                'rank_among_others' => CompetitionService::rankAmongOthers($request->user(), $this->resource),
            ]),
            $this->mergeWhen($this->category == 'cup', [
                'type' => $this->type,
                'starting_round' => \App\Http\Resources\ApiFootball\Rounds\IndexResource::make($this->round),
                'status' => $this->cup_status,
                'out' => CompetitionService::out($request->user(), $this->resource),
            ]),
            'participants' => $this->participants,
            'user' => SimpleResource::make($this->user),
            // 'competitors_count' => $this->competitors()->count(),
            'competitors_count' => count(CompetitionService::competitionUsers($this->id)),
            'group_points' => CompetitionService::groupPoints($this->league, $this->id),
            'winner' => CompetitionService::winner($request->user(), $this->resource),
            'is_subscribed_in_that_league' => $request->user('sanctum')->leagues()->wherePivot('season_id', $this->season->id)->whereId($this->league->id)->first() ? true : false,
            'is_joined_in_that_competition' => $this->competitors()->whereUserId($request->user('sanctum')->id)->count() ? true : false,
        ];
    }
}
