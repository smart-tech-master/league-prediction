<?php

namespace App\Http\Resources\ApiFootball\Rounds;

use Illuminate\Http\Resources\Json\JsonResource;

class SimpleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $response = [
            'id' => $this->id,
            // 'name' => $this->name,
            'name' => (string)$this->sl,
        ];

        return $response;
    }
}
