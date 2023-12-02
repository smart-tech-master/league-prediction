<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'notifications.*' => 'nullable|accepted',
            'body_text.*' => 'required',
            'sizes.*' => 'nullable|integer',
            'leagues.*.display_in_app' => 'sometimes|accepted',
            'leagues.*.appearance_order' => 'nullable|integer',
            'contact.*' => 'required',
        ];
    }
}
