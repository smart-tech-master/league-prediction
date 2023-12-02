<?php

namespace App\Http\Resources\Paginations\Ranks\Leagues;

use App\Services\RankService;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowResource extends JsonResource
{
    protected $user;

    public function user($user){
        $this->user = $user;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $season = $request->route()->parameter('season');

        $users = [
            'world' => RankService::users($this->resource, $season),
            'continent' => RankService::users($this->resource, $season, 'continent', $this->user->country->continent),
            'country' => RankService::users($this->resource, $season, 'country', $this->user->country)
        ];

        return [
            'world' => [
                'position' => $users['world']->firstWhere('user.id', $this->user->id)['position'] ?? null,
                'total' => $users['world']->count(),
            ],
            'continent' => [
                'position' => $users['continent']->firstWhere('user.id', $this->user->id)['position'] ?? null,
                'total' => $users['continent']->count(),
            ],
            'country' => [
                'position' => $users['country']->firstWhere('user.id', $this->user->id)['position'] ?? null,
                'total' => $users['country']->count(),
            ],
            'points' => RankService::points($this->user, $this->resource, $season)
        ];
    }

    public static function collection($resource)
    {
        return new ResourceCollection($resource);
    }
}
