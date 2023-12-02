<?php

namespace App\Http\Resources\Account;

use App\Http\Resources\L10n\LocaleResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\Users\IndexResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            $this->merge(function () {
                return IndexResource::make($this);
            }),
            'email' => $this->email,
            'dob' => $this->dob,
            'role' => $this->role,
            'created_at' => $this->created_at,
            'receive_notifications' => $this->receive_notifications,
            'device_token' => $this->device_token,
            'device_platform' => $this->device_platform,
            'locale' => LocaleResource::make($this->locale),
            'unread_notifications' => NotificationResource::collection($this->unreadNotifications()->get()),
            'provider' => $this->provider,
            'provider_id' => $this->provider_id,
            'email_verified_at' => $this->email_verified_at,
            'leagues_count' => $this->competitions()->count(),
            'private_leagues_count' => $this->createdCompetitions()
                ->get()
                ->filter(function ($competitions) {
                    return $competitions->category == 'league';
                })
                ->count(),
            'private_cups_count' => $this->createdCompetitions()
                ->get()
                ->filter(function ($competitions) {
                    return $competitions->category == 'cup';
                })
                ->count(),
        ];
    }
}
