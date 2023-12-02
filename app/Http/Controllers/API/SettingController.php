<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index(){
        $settings = Setting::where('type', 'like', 'contact.%')
            ->get()
            ->mapWithKeys(function ($setting) {
                return [$setting->type => $setting->content];
            })
            ->undot();

        return response()->json(['data' => $settings]);
    }
}
