<?php

namespace App\Http\Resources\Paginations\Ranks\Leagues;

use Illuminate\Http\Resources\Json\ResourceCollection as JsonResourceCollection;

class ResourceCollection extends JsonResourceCollection
{
    protected $user;

    public function user($user){
        $this->user = $user;
        return $this;
    }

    public function toArray($request)
    {
        return $this->collection->map(function (IndexResource $resource) use ($request) {
            return $resource->user($this->user)->toArray($request);
        })->all();
    }
}
