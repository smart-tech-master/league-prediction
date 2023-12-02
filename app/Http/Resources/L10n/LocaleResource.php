<?php

namespace App\Http\Resources\L10n;

use Illuminate\Http\Resources\Json\JsonResource;

class LocaleResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'native' => $this->native,
        ];
    }
}
