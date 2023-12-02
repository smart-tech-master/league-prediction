<?php

namespace App\Http\Resources\CustomFootball;

use App\Http\Resources\Users\SimpleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            'id' => $this->id,
            'object' => 'custom-football.competition.chat',
            'comment' => utf8_decode($this->comment),
            'created_at' => $this->created_at,
            'user' => SimpleResource::make($this->user),
            $this->mergeWhen($this->parent, [
                'chat' => ChatResource::make($this->parent),
            ]),
            'message' => $this->user->username . ($this->parent ? ' reply ' : ' comment ') . 'as ' . utf8_decode($this->comment),
            'competition_id' => $this->competition->id,
            'competition_title' => $this->competition->title,
            'competitors' => $this->competition->competitors()->count(),
        ];
    }
}
