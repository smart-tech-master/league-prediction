<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Models\Setting;
use App\Scopes\SequentialLeagueScope;
use Illuminate\Http\Request;
use App\Services\ApiFootball\RoundService;
use App\Services\ApiFootball\FixtureService;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings.index')
            ->withSeason(Season::first())
            ->withLeagues(League::withoutGlobalScope(SequentialLeagueScope::class)->get())
            ->withSettings(collect(Setting::all()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SettingRequest $request)
    {
        ini_set('max_execution_time', 7200);

        Setting::where('type', 'like', 'notifications.%')->delete();
        Setting::where('type', 'like', 'body_text.%')->delete();
        Setting::where('type', 'like', 'sizes.%')->delete();
        Setting::where('type', 'like', 'leagues.%')->delete();
        Setting::where('type', 'like', 'contact.%')->delete();

        if ($request->filled('notifications')) {
            foreach ($request->notifications as $key => $value) {
                if (filled($value)) {
                    $setting = Setting::forceCreate([
                        'type' => 'notifications.' . $key,
                        'content' => true,
                    ]);
                }
            }
        }

        if ($request->filled('body_text')) {
            foreach ($request->body_text as $key => $value) {
                if (filled($value)) {
                    $setting = Setting::forceCreate([
                        'type' => 'body_text.' . $key,
                        'content' => $value,
                    ]);
                }
            }
        }

        if ($request->filled('sizes')) {
            foreach ($request->sizes as $key => $value) {
                if (filled($value)) {
                    $setting = Setting::forceCreate([
                        'type' => 'sizes.' . $key,
                        'content' => $value,
                    ]);
                }
            }
        }

        if ($request->filled('leagues')) {
            foreach ($request->leagues as $leagueId => $values) {
                if ($request->filled('leagues.' . $leagueId . '.display_in_app')) {
                    if ($request->filled('leagues.' . $leagueId . '.display_in_app')) {
                        $setting = Setting::forceCreate([
                            'type' => 'leagues.' . $leagueId . '.display_in_app',
                            'content' => true,
                        ]);
                    }
                }
                if ($request->filled('leagues.' . $leagueId . '.appearance_order')) {
                    if ($request->filled('leagues.' . $leagueId . '.appearance_order')) {
                        $setting = Setting::forceCreate([
                            'type' => 'leagues.' . $leagueId . '.appearance_order',
                            'content' => $request->input('leagues.' . $leagueId . '.appearance_order'),
                        ]);
                    }
                }
            }
        }

        if ($request->filled('contact')) {
            foreach ($request->contact as $key => $value) {
                if (filled($value)) {
                    $setting = Setting::forceCreate([
                        'type' => 'contact.' . $key,
                        'content' => $value,
                    ]);
                }
            }
        }

        if($request->filled('season')) {
            $season = Season::first();
            $season->year = $request->season;
            $season->save();
        }

        flash()->addSuccess('Settings has been updated successfully.');

        (new RoundService())->init();

        (new fixtureService())->restore();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
