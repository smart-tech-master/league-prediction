<?php

namespace App\Services\CustomFootball;

use App\Http\Resources\CustomFootball\Competitions\IndexResource;
use App\Models\ApiFootball\Season;
use App\Models\CustomFootball\Competition;
use Illuminate\Http\Request;

class SearchService
{
    public function result(Request $request)
    {
        return IndexResource::collection(self::{$request->type}($request));
    }

    private function joined_by(Request $request)
    {
        // This type of League/Cup will appear by entering only
        //the code of that Private League/Cup in the search
        //field. Users who have the code can enter by only
        //clicking on that League/Cup.
        $private = Competition::whereJoinedBy('private')
            ->where('code', 'like', '%' . $request->q . '%q')
            ->whereBelongsTo(Season::first())
            ->get();

        // Users can enter another’s League/Cup
        //by searching for the title, code, owner (username) of
        //the League/Cup in the search field placed on the “My
        //Leagues” Page.
        $general = Competition::whereJoinedBy('general')
            ->where(function ($query) use ($request) {
                $query->orWhere('title', 'like', '%' . $request->q . '%')
                    //->orWhere('code', 'like', '%' . $request->q . '%')
                    ->orWhereRaw('convert(code using utf8) like "%' . $request->q . '%"')
                    ->orWhereRelation('user', 'username', 'like', '%' . $request->q . '%');
            })
            ->whereBelongsTo(Season::first())
            ->get();

        $competitions = $private->merge($general);

        if ($request->filled('category')) {
            $competitions = $competitions->filter(function ($competition) use ($request) {
                return $competition->category == $request->category;
            });
        }

        return $competitions;
    }

    private function popular(Request $request)
    {
        // 1. Only the General League/Cup will appear on this page
        //2. General League/Cup with the most interactive in terms of
        //comments and predictions
        //3. General League/Cup in terms of the number of users that
        //have joined (the higher number of users join the room, the
        //greater chance that this private League/Cup will appear on
        //this page)
//        $competitions = Competition::/*whereJoinedBy('general')
//            ->*/where(function ($query) use ($request) {
//                $query->orWhere('title', 'like', '%' . $request->q . '%')
//                    //->orWhere('code', 'like', '%' . $request->q . '%')
//                    ->orWhereRaw('convert(code using utf8) like "%' . $request->q . '%"')
//                    ->orWhereRelation('user', 'username', 'like', '%' . $request->q . '%');
//            })
//            ->whereBelongsTo(Season::first())
//            ->get();

        $generals = Competition::whereJoinedBy('general');

        if ($request->filled('q')) {
            $generals = $generals->where(function ($query) use ($request) {
                $query->orWhere('title', 'like', '%' . $request->q . '%')
                    //->orWhere('code', 'like', '%' . $request->q . '%')
                    ->orWhereRaw('convert(code using utf8) like "%' . $request->q . '%"')
                    ->orWhereRelation('user', 'username', 'like', '%' . $request->q . '%');
            });
        }

        // $generals = $generals->whereBelongsTo(Season::first())->get();
        $allowed_leagues = (new CompetitionService())->competitionableLeagues();
        $generals = $generals->whereBelongsTo(Season::first())->whereIn('league_id', $allowed_leagues)->get();

        if ($request->filled('q')) {
            $privates = Competition::whereJoinedBy('private')->where(function ($query) use ($request) {
                $query->orWhereRaw('convert(code using utf8) like "%' . $request->q . '%"')
                    ->orWhere('title', 'like', '%' . $request->q . '%');
            })->whereBelongsTo(Season::first())->get();
        }

        if (isset($privates)) {
            $competitions = $generals->merge($privates);
        } else {
            $competitions = $generals;
        }

        //echo '<pre>';print_r($competitions);exit;
        if ($request->filled('category')) {
            $competitions = $competitions->filter(function ($competition) use ($request) {
                return $competition->category == $request->category;
            });
        }
        //echo '<pre>';print_r($competitions);exit;
        $competitions->map(function ($competition) {
            return [
                'object' => IndexResource::make($competition)->resolve(),
                'competitors' => $competition->competitors()->count(),
                'chats' => $competition->chats()->count(),
            ];
        })
            ->sortBy(['competitors' => 'desc', 'chats' => 'desc'])
            ->map(function ($competition) {
                return $competition['object'];
            });

        return collect($competitions)->paginate(15);
    }
}
