<?php

namespace App\Http\Resources;

use App\Http\Resources\Users\SimpleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
        ];

        if($this->user){
            $response = array_merge($response, [
                'user' => SimpleResource::make($this->user),
            ]);
        }

        return $response;
    }
}
