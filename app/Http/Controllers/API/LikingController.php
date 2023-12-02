<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LikingRequest;
use App\Http\Resources\Users\IndexResource;
use App\Models\User;

class LikingController extends Controller
{
    public function store(LikingRequest $request){

        $user = User::findOrFail($request->input('user'));

        $liking = $user->likings()->whereBelongsTo($request->user())->whereType('like')->first();

        if($liking){
            $liking->delete();
        }else{
            $liking = $user->likings()->forceCreate([
                'user_id' => $request->user()->id,
                'type' => $request->type,
            ]);
        }

        return IndexResource::make($user);
    }
}
