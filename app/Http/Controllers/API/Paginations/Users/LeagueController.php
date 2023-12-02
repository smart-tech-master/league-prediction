<?php

namespace App\Http\Controllers\API\Paginations\Users;

use App\Http\Controllers\Controller;
use App\Models\ApiFootball\Season;
use App\Models\User;
use App\Services\Paginations\Ranks\LeagueService;

class LeagueController extends Controller
{
    public function index(User $user){
        $season = Season::first();
        return LeagueService::index($user, $season);
    }
}
