<?php

namespace App\Http\Requests;

use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdRequest extends FormRequest
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
            'file' => 'nullable|image|mimes:png,jpeg,jpg|max:2048',
        ];

        switch ($this->route()->parameter('ad')->type) {
            case 'banner':
                $rules = array_merge($rules, [
                    'country' => 'required|in:' . Country::get()->pluck('id')->implode(','),
                    'time_of_appearance' => 'required|integer',
                    'started_at' => ['required', 'date', 'date_format:Y-m-d', 'before:ended_at'],
                    'ended_at' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:started_at'],
                    'link_type' => 'required|in:external,internal',
                    //'link' => 'required|string',
                    'link_external' => 'required_if:link_type,=,external|max:255',
                    'link_internal' => 'required_if:link_type,=,internal|max:255',
                ]);
                break;

            case 'tutorial-screen':
                $rules = array_merge($rules, [
                    'title' => 'nullable|string|max:255',
                    'description' => 'nullable',
                    'sl' => 'required|integer'
                ]);
                break;
        }

        return $rules;
    }
}
