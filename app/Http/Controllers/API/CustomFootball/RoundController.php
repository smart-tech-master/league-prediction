<?php

namespace App\Http\Controllers\API\CustomFootball;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomFootball\IndexRoundRequest;
use App\Http\Resources\ApiFootball\Rounds\SimpleResource;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Services\CustomFootball\RoundService;

class RoundController extends Controller
{
    public function index(IndexRoundRequest $request, League $league, Season $season)
    {
        return SimpleResource::collection((new RoundService())->index($request, $league, $season));
    }
}
