<?php

namespace App\Http\Requests\CustomFootball;

use App\Models\Setting;
use App\Services\CustomFootball\CompetitionService;
use Illuminate\Foundation\Http\FormRequest;

class StoreCompetitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $logoMaxSize = Setting::whereType('sizes.profile_picture_max')->first()->content ?? 0;

        $rules = [
            'league' => 'required|in:' . (new CompetitionService())->competitionableLeagues()->implode(','),
            'title' => 'required|string|max:250',
            'description' => 'nullable',
            'play_for' => 'required|in:fun,prize',
            'contact' => 'nullable|required_if:play_for,prize',
            'joined_by' => 'required|in:general,private',
            'category' => 'required|in:league,cup',
            'participants' => 'nullable|integer',
            'logo' => 'nullable|image|mimes:png,jpeg,jpg|max:' . $logoMaxSize,
            'country' => 'required|array',
        ];
        if($this->filled('league') && $this->filled('type') && $this->filled('round')){
            $rules = array_merge($rules, [
                'type' => 'nullable|in:home-and-away,one-match',
                'round' => 'required_with:type|integer|in:' . (new CompetitionService())->validStartingRounds($this)->implode(','),
            ]);
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'league' => trans('others.league'),
            'title' => trans('others.title'),
            'description' => trans('others.description'),
            'play_for' => trans('others.play_for'),
            'contact' => trans('others.contact'),
            'joined_by' => trans('others.joined_by'),
            'participants' => trans('others.participants'),
            'type' => trans('others.type'),
            'round' => trans('others.round')
        ];
    }
}
