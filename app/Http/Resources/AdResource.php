<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
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
            'id' => $this->id,
            // 'object' => 'ad',
            'type' => $this->type,
            'file' => $this->file,
            'action_type' => $this->action_type,
            // $this->mergeWhen(in_array($this->type, ['launch-screen', 'banner']), [
            //     'link_type' => $this->link_type,
            //     'link' => $this->link,
            //     'country' => CountryResource::make($this->country)
            // ]),
            $this->mergeWhen(in_array($this->type, ['banner', 'logo']), [
                'link_type' => $this->link_type,
                'link' => $this->link,
                'country' => CountryResource::make($this->country)
            ]),
            // $this->mergeWhen($this->type == 'banner', [
            //     'started_at' => $this->started_at,
            //     'ended_at' => $this->ended_at,
            //     'time_of_appearance' => $this->time_of_appearance,
            //     'country' => CountryResource::make($this->country),
            // ]),
            $this->mergeWhen($this->type == 'tutorial-screen', [
                'title' => $this->title,
                'description' => $this->description,
                'sl' => $this->sl,
            ]),
        ];
    }
}
