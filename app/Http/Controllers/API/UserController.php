<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\ShowResource;
use App\Models\User;

class UserController extends Controller
{
    public function show(User $user){
        return ShowResource::make($user);
    }
}
