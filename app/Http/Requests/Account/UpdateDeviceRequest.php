<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeviceRequest extends FormRequest
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
            'device_token' => 'required|string|max:250',
            'device_platform' => 'required|in:android,ios',
        ];
    }

    public function attributes()
    {
        return [
            'device_token' => trans('others.device_token'),
            'device_platform' => trans('others.device_platform'),
        ];
    }
}
