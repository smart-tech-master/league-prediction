<?php

namespace App\Http\Resources\CustomFootball\Fixtures;

use App\Http\Resources\Users\SimpleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $fixture;

    public function fixture($fixture)
    {
        $this->fixture = $fixture;
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
        $user = $this;

        return [
            $this->merge(function () {
                return SimpleResource::make($this);
            }),
            'subscribed_at' => $this->fixture->competition->competitors()->find($this->id)->pivot->created_at,
            'points' => $this->fixture->predictions()->get()
                ->filter(function ($prediction) use ($user) {
                    return $prediction->user->id == $user->id;
                })
                ->sum('points'),
        ];
    }

    public static function collection($resource)
    {
        return new UserResourceCollection($resource);
    }
}
