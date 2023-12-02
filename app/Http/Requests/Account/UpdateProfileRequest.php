<?php

namespace App\Http\Requests\Account;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
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
        $profilePictureMaxSize = Setting::whereType('sizes.profile_picture_max')->first()->content ?? 0;

        return [
            'full_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user()->id,
            'bio' => 'nullable',
            'profile_picture' => 'nullable|image|mimes:png,jpeg,jpg|max:' . $profilePictureMaxSize,

            'username' => 'nullable|string|max:255|not_in:super-admin,public-user|unique:users',
            'country' => 'nullable|integer',
            'dob' => 'nullable|date|date_format:Y-m-d',
        ];
    }

    public function attributes()
    {
        return [
            'full_name' => trans('others.full_name'),
            'email' => trans('others.email'),
            'bio' => trans('others.bio'),
            'profile_picture' => trans('others.profile_picture'),

            'username' => trans('others.username'),
            'country' => trans('others.country'),
            'dob' => trans('others.dob'),
        ];
    }
}
