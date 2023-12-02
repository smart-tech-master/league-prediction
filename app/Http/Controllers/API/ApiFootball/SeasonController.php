<?php

namespace App\Http\Controllers\API\ApiFootball;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiFootball\SeasonResource;
use App\Models\ApiFootball\Season;
use App\Scopes\CurrentSeasonScope;

class SeasonController extends Controller
{
    public function index(){
        return SeasonResource::collection(Season::withoutGlobalScope(CurrentSeasonScope::class)->get());
    }
}
