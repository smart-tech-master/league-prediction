<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
        $rules = [
            'content' => 'required',
        ];

        if(in_array($this->route()->parameter('page')->type, ['contact-us', 'about-us'])){
            $rules = array_merge($rules, ['file' => 'nullable|image|mimes:png,jpeg,jpg|max:2048',]);
        }

        return $rules;
    }
}
