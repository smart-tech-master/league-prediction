<?php

namespace App\Http\Requests;

use App\Models\Country;
use App\Models\ApiFootball\League;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $rules = [
            'type' => 'required|in:banner,tutorial-screen,logo',
            'file' =>'required|file|mimes:gif,png,jpeg,video/mp4|max:2048',
        ];

        if($this->filled('type') && $this->get('type') == 'banner'){
            $rules = array_merge($rules, [
                'link_type' => 'required|in:external,internal',
                'link_external' => 'required_if:link_type,=,external|max:255',
                'link_internal' => 'required_if:link_type,=,internal|max:255',
                //'country' => 'required|in:' . Country::get()->pluck('id')->implode(','),
                'time_of_appearance' => 'required|integer',
                'started_at' =>  ['required', 'date','date_format:Y-m-d', 'before:ended_at'],
                'ended_at'  => ['required', 'date','date_format:Y-m-d','after_or_equal:started_at'],
                'file' =>'required|file|mimes:gif,png,jpeg,video/mp4|max:2048',
                'countries' => 'required|array|min:1',
                'countries.*' => 'required|in:' . Country::get()->pluck('id')->implode(','),
            ]);
        }elseif($this->filled('type') && $this->get('type') == 'logo'){
            $rules = array_merge($rules, [
                'link_type' => 'required|in:external,internal',
                'link_external' => 'required_if:link_type,=,external|max:255',
                'link_internal' => 'required_if:link_type,=,internal|max:255',
                'time_of_appearance' => 'required|integer',
                'started_at' =>  ['required', 'date','date_format:Y-m-d', 'before:ended_at'],
                'ended_at'  => ['required', 'date','date_format:Y-m-d','after_or_equal:started_at'],
                'file' =>'required|file|mimes:gif,png,jpeg,video/mp4|max:2048',
                'countries' => 'required|array|min:1',
                'countries.*' => 'required|in:' . Country::get()->pluck('id')->implode(','),
                'leagues' => 'required|array|min:1',
                'leagues.*' => 'required|in:' . League::get()->pluck('id')->implode(','),
            ]);
        }elseif ($this->filled('type') && $this->get('type') == 'tutorial-screen'){
            $rules = array_merge($rules, [
                'title' => 'nullable|string|max:255',
                'description' => 'nullable',
                'sl' => 'required|integer',
            ]);
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'link_external' => 'ad link',
            'link_internal' => 'ad link',
            'time_of_appearance' => 'time of ad appearance',
            'started_at' => 'from',
            'ended_at' => 'to',
            'countries' => 'location'
        ];
    }
}
