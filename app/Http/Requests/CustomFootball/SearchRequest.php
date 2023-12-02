<?php

namespace App\Http\Requests\CustomFootball;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
        return [
            'q' => 'nullable',
            'type' => 'required|in:joined_by,popular',
            'category' => 'nullable|in:league,cup',
        ];
    }

    public function attributes()
    {
        return [
            'q' => trans('others.q'),
            'type' => trans('others.type'),
        ];
    }
}
