<?php

namespace App\Http\Controllers\API\CustomFootball;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomFootball\StoreCompetitionRequest;
use App\Http\Resources\CustomFootball\Competitions\IndexResource;
use App\Http\Resources\CustomFootball\Competitions\ShowResource;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Models\CustomFootball\Competition;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate(['category' => 'required|in:league,cup']);

        $competitions = Competition::whereBelongsTo($request->user())->latest()->whereJoinedBy('general')->get()
            ->merge($request->user()->competitions()->whereJoinedBy('general')->get())
            ->filter(function ($competition) use ($request) {
                return $competition->category == $request->category;
            });

        return IndexResource::collection($competitions->paginate(15));
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
    public function store(StoreCompetitionRequest $request)
    {
        $competition = DB::transaction(function () use ($request){
            $league = League::findOrFail($request->league);
            $season = Season::first();
            $competition = Competition::forceCreate([
                'league_id' => $league->id,
                'season_id' => $season->id,
                'title' => $request->title,
                'description' => $request->input('description', null),
                'play_for' => $request->play_for,
                'category' => $request->category,
                'contact' => $request->input('contact', null),
                'joined_by' => $request->joined_by,
                'participants' => $request->input('participants'),
                'type' => $request->input('type', null),
                'user_id' => $request->user()->id,
                'round_id' => $request->input('round', null),
                'country_id' => json_encode($request->input('country')),
                'logo' => $request->hasFile('logo') ? (\FileUpload::put($request->logo)) : null,
            ]);

            $request->user()->leagues()
                ->wherePivot('season_id', $season->id)
                ->syncWithoutDetaching([$league->id => ['season_id' => $season->id]]);
            (new SubscriptionService())->createPostMatchPositioning($request->user(), $league, $season);

            $request->user()->competitions()->syncWithoutDetaching([$competition->id]);

            return $competition;
        });

        return IndexResource::make($competition);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Competition $competition)
    {
        return ShowResource::make($competition);
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
    public function update(Request $request, Competition $competition)
    {
        $competition->description = $request->description;
        $competition->logo = $request->hasFile('logo') ? (\FileUpload::put($request->logo)) : null;
        $competition->update();

        return ShowResource::make($competition);
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
