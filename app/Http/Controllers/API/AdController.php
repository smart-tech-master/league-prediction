<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdResource;
use App\Models\Ad;
use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index(Request $request){
        $request->validate([
            'type' => 'required|in:launch-screen,banner,tutorial-screen',
            'country.code' => 'required_if:type,==,banner|in:' . Country::get()->pluck('code')->implode(','),
        ]);

        $ads = Ad::whereType($request->type);

        if($request->filled('country.code') && $request->type == 'banner'){
            $ads = $ads->whereBelongsTo(Country::whereCode($request->input('country.code', 0))->first())
            ->whereDate('started_at', '<=', Carbon::now())
            ->whereDate('ended_at', '>=', Carbon::now())
                ->latest();
        }

        return AdResource::collection([]);
        if(in_array($request->type, ['launch-screen', 'banner'])) {
            return AdResource::collection($ads->latest()->get());
        }elseif ($request->type == 'tutorial-screen'){
            return AdResource::collection($ads->orderBy('sl', 'asc')->get());
        }
    }
}
