<?php

namespace App\Http\Resources\Users;

use App\Services\CustomFootball\CompetitionService;
use App\Services\RankService;
use Illuminate\Http\Resources\Json\JsonResource;

class CompetitorResource extends JsonResource
{
    protected $competition;

    public function competition($competition)
    {
        $this->competition = $competition;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            $this->merge(function () {
                return SimpleResource::make($this);
            }),
            'points' => RankService::points($this->resource, $this->competition->league, $this->competition->season),
            'round_points' => CompetitionService::roundPoints($this->resource, $this->competition->league, $this->competition->season),
        ];
    }

    public static function collection($resource)
    {
        return new CompetitorResourceCollection($resource);
    }
}
