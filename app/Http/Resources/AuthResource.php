<?php

namespace App\Http\Resources;

use App\Http\Resources\Account\ProfileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
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
            'user' => ProfileResource::make($this),
            'bearer_token' => $this->createToken('bearer_token')->plainTextToken,
        ];
    }
}
