<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LikingRequest extends FormRequest
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
            'type' => 'required|in:like',
            'user' => 'required|integer|not_in:' . $this->user()->id,
        ];
    }

    public function attributes()
    {
        return [
            'type' => trans('others.type'),
            'user' => trans('others.user'),
        ];
    }
}
