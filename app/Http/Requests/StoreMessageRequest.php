<?php

namespace App\Http\Requests;

use App\Models\ApiFootball\League;
use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:250',
            'text' => 'required_without:picture',
            'picture' => 'required_without:text|image|mimes:png,jpeg,jpg|max:2048',
            'send_to' => 'required|in:users,countries,leagues',
            'countries' => 'required_if:send_to,==,countries|array|min:1',
            'countries.*' => 'required|integer|in:' . Country::get()->pluck('id')->implode(','),
            'leagues' => 'required_if:send_to,==,leagues|array|min:1',
            'leagues.*' => 'required|integer|in:' . League::get()->pluck('id')->implode(','),
        ];
    }

    public function attributes()
    {
        return [
            'link' => 'ad link',
            'time_of_appearance' => 'time of ad appearance',
            'started_at' => 'from',
            'ended_at' => 'to',
            'country' => 'location'
        ];
    }
}
