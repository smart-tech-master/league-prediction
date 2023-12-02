<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
use App\Http\Requests\UpdateAdRequest;
use App\Models\Ad;
use App\Models\ApiFootball\League;
use App\Models\Country;
use Illuminate\Http\Request;

class AdController extends Controller
{

    public function index(Request $request)
    {
        $request->validate(['type' => 'required|in:launch-screen,banner,tutorial-screen,logo']);

        $ads = Ad::whereType($request->type)->latest()->get();

        $view = view('ads.index');

        if ($request->type == 'banner' || $request->type == 'logo') {
            if ($request->filled('countries') && is_array($request->countries) && !empty($request->countries)) {
                if(! in_array('', $request->countries)) {
                    $ads = $ads->filter(function ($ad) use ($request) {
                        return in_array($ad->country->id, $request->countries);
                    });
                }
            }

            $view = $view->withCountries(Country::all());

            if ($request->type == 'logo') {
                if ($request->filled('leagues') && is_array($request->leagues) && !empty($request->leagues)) {
                    if(! in_array('', $request->leagues)) {
                        $ads = $ads->filter(function ($ad) use ($request) {
                            return in_array($ad->league_id, $request->leagues);
                        });
                    }
                }
    
                $view = $view->withLeagues(League::all());
            }
        }

        return $view->withAds($ads);
    }

    public function create(Request $request)
    {
        $request->validate(['type' => 'required|in:banner,tutorial-screen,logo']);

        $countries = Country::orderBy('name')->get();

        return view('ads.create.' . $request->type)->withCountries($countries)->withLeagues(League::all());
    }

    public function store(StoreAdRequest $request)
    {
        if ($request->type == 'banner') {
            foreach ($request->countries as $country) {
                $ad = Ad::forceCreate([
                    'file' => \FileUpload::put($request->file),
                    'country_id' => $country,
                    'time_of_appearance' => $request->time_of_appearance,
                    'link_type' => $request->link_type,
                    'link' => $request->{'link_' . $request->link_type},
                    'type' => 'banner',
                    'started_at' => $request->started_at,
                    'ended_at' => $request->ended_at,
                ]);
            }
        } elseif ($request->type == 'logo') {
            foreach ($request->countries as $country) {
                foreach($request->leagues as $league) {                    
                    $ad = Ad::forceCreate([
                        'file' => \FileUpload::put($request->file),
                        'country_id' => $country,
                        'league_id' => $league,
                        'time_of_appearance' => $request->time_of_appearance,
                        'link_type' => $request->link_type,
                        'link' => $request->{'link_' . $request->link_type},
                        'type' => 'logo',
                        'started_at' => $request->started_at,
                        'ended_at' => $request->ended_at,
                    ]);
                }
            }
        } elseif ($request->type == 'tutorial-screen') {
            $ad = Ad::forceCreate([
                'file' => \FileUpload::put($request->file),
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'type' => 'tutorial-screen',
                'sl' => $request->sl,
            ]);
        }

        flash()->addSuccess('Data has been inserted successfully.');

        return redirect()->back();

    }

    public function edit(Ad $ad)
    {
        $view = view('ads.edit.' . $ad->type)->withAd($ad);

        if ($ad->type == 'banner' || $ad->type == 'logo') {
            $view = $view->withCountries(Country::orderBy('name')->get())->withLeagues(League::all());
        }

        return $view;
    }


    public function update(UpdateAdRequest $request, Ad $ad)
    {
        if ($request->hasFile('file')) {
            $ad->file = \FileUpload::put($request->file);
        }

        if (in_array($ad->type, ['launch-screen', 'banner', 'logo'])) {
            $ad->link_type = $request->link_type;
            //$ad->link = $request->link;
            $ad->link = $request->{'link_' . $request->link_type};

            if ($ad->type == 'banner') {
                $ad->country_id = $request->country;
                $ad->time_of_appearance = $request->time_of_appearance;
                $ad->started_at = $request->started_at;
                $ad->ended_at = $request->ended_at;
            }

            if ($ad->type == 'logo') {
                $ad->country_id = $request->country;
                $ad->league_id = $request->league;
                $ad->time_of_appearance = $request->time_of_appearance;
                $ad->started_at = $request->started_at;
                $ad->ended_at = $request->ended_at;
            }
        } elseif ($ad->type == 'tutorial-screen') {
            $ad->title = $request->input('title');
            $ad->description = $request->input('description');
            $ad->sl = $request->sl;
        }

        $ad->save();

        flash()->addSuccess('Data has been updated succesfully.');

        return redirect()->back();
    }


    public function destroy(Ad $ad)
    {
        $ad->delete();
        return redirect()->back()->withSuccess('Data Successfully Deleted!!!');
    }

    public function optMail() {
        return view('email.pin-code-template');
    }


}
