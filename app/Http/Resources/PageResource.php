<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'type' => $this->type,
            'icon' => $this->icon,
            'universal_title' => $this->universal_title,
            'title' => $this->title,
            'content' => $this->content,
            $this->mergeWhen(in_array($this->type, ['contact-us', 'about-us']), [
                'picture' => $this->picture,
            ]),
        ];
    }
}
