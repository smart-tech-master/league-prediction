<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApiFootball\Season;
use App\Services\RankService;
use Illuminate\Http\Request;

class RankController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'league' => 'integer',
            'season' => 'integer',
        ]);

        return response()->json([
            'data' => RankService::rank($request->user('sanctum'), $request->input('league', null), $request->input('season', Season::first()->id))
        ]);
    }
}
