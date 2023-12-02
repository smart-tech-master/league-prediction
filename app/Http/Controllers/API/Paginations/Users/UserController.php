<?php

namespace App\Http\Controllers\API\Paginations\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Paginations\Users\ShowResource;
use App\Models\User;

class UserController extends Controller
{
    public function show(User $user){
        ini_set('max_execution_time', 3600);
        return ShowResource::make($user);
    }
}
