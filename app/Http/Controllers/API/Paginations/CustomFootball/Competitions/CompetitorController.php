<?php

namespace App\Http\Controllers\API\Paginations\CustomFootball\Competitions;

use App\Http\Controllers\Controller;
use App\Http\Resources\Paginations\CustomFootball\Competitions\CompetitorResource;
use App\Models\CustomFootball\Competition;
use App\Services\Paginations\CustomFootball\CompetitionService;
use Illuminate\Http\Request;

class CompetitorController extends Controller
{
    public function index(Request $request, Competition $competition){
        return CompetitorResource::collection(CompetitionService::getLeagueCompetitorsByCompetitionId($competition));
    }
}
