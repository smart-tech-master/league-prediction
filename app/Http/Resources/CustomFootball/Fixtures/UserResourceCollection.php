<?php

namespace App\Http\Resources\CustomFootball\Fixtures;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserResourceCollection extends ResourceCollection
{
    protected $fixture;

    public function fixture($fixture){
        $this->fixture = $fixture;
        return $this;
    }

    public function toArray($request)
    {
        return $this->collection->map(function (UserResource $resource) use ($request){
            return $resource->fixture($this->fixture)->toArray($request);
        })->all();
    }
}
