<?php

namespace App\Http\Requests\CustomFootball;

use Illuminate\Foundation\Http\FormRequest;

class IndexRoundRequest extends FormRequest
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
            'type' => 'required|in:home-and-away,one-match',
            'participants' => 'required|integer|in:4,8,16,32,64,128'
        ];
    }
}
