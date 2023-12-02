<?php

namespace App\Http\Resources\CustomFootball\Rounds;

use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
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
            // 'name' => $this->name,
            'name' => (string)$this->sl,
            'fixtures' => \App\Http\Resources\CustomFootball\Fixtures\IndexResource::collection($this->fixtures()->get()),
        ];
    }
}
