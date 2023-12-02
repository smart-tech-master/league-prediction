<?php

namespace App\Http\Controllers\API\Paginations\Ranks;

use App\Http\Controllers\Controller;
use App\Http\Resources\Paginations\Ranks\Leagues\IndexResource;
use App\Http\Resources\Paginations\Ranks\Leagues\ShowResource;
use App\Http\Resources\Paginations\Ranks\Leagues\TopsResource;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Services\Paginations\Ranks\LeagueService;
use App\Services\RankService;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
    public function index(Request $request, Season $season){
        return response()->json(['data' => LeagueService::index($request->user(), $season)]);
    }

    public function show(Request $request, Season $season, League $league){
        // if($request->filled('tops') && in_array($request->tops, ['world', 'continent', 'country'])){
        //     $toppers = LeagueService::show($request, $league, $season);
        //     //$this->_print_r($toppers);
        //     return TopsResource::collection($toppers);
        // }
//        if($request->filled('tops') && in_array($request->tops, ['world', 'continent', 'country'])){
//            $toppers = RankService::toppers($league, $season, $request->tops, ($request->tops == 'continent' ? $request->user()->country->continent : $request->user()->country));
//            //$this->_print_r($toppers);
//            return TopsResource::collection($toppers);
//        }

        if($request->filled('tops') && in_array($request->tops, ['world', 'continent', 'country'])){
            $toppers = LeagueService::show($request, $league, $season);
            return response()->json(['data' => $toppers]);
            // print_r(json_encode($toppers));exit;
            // return TopsResource::collection($toppers);
        }

        // if($request->filled('tops') && in_array($request->tops, ['world', 'continent', 'country'])){
        //     $toppers = LeagueService::show($request, $league, $season);

        //     foreach ($toppers as $key => $topper) {
        //         $topper->position = $topper->{'current_' . $request->tops . '_position'};
        //         $topper->pointsFromLastRound = (int)$topper->total_points_on_last_round;
        //         $topper->points = (int)$topper->total_points;
        //     }
        //     //$this->_print_r($toppers);
        //     return TopsResource::collection($toppers);
        // }
    }
}
