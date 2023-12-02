<?php
namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CompetitorResourceCollection extends ResourceCollection{
    protected $competition;

    public function competition($competition){
        $this->competition = $competition;
        return $this;
    }

    public function toArray($request)
    {
        return $this->collection->map(function (CompetitorResource $resource) use ($request) {
            return $resource->competition($this->competition)->toArray($request);
        })->all();
    }
}
