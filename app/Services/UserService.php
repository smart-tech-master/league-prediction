<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class UserService
{
    public function avatar(User $user){
        if(is_null($user->profile_picture)){
            return asset('img/logo.png');
        }else{
            if(Str::startsWith($user->profile_picture, '/storage/')){
                return asset($user->profile_picture);
            }
        }

        return $user->profile_picture;
    }
}
