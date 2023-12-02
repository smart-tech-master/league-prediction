<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if($this->filled('login_with')){
            $col= filter_var($this->login_with, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $this->merge([
                $col => $this->login_with,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if($this->has('unique_id')) {
            return [
                'unique_id' => 'required|string',
            ];
        }

        if(! is_null($this->route()->parameter('provider'))){
            return [
                'provider' => 'required|string',
                'email' => 'required|email',
                'full_name' => 'nullable|max:254',
                'profile_picture' => 'nullable|url',
            ];
        }

        return [
            'email' => 'required_without:username|email',
            'username' => 'required_without:email',
            'password' => 'required|string'
        ];
    }

    public function attributes()
    {
        if(! is_null($this->route()->parameter('provider'))){
            return [
                'provider' => trans('others.provider'),
                'email' => trans('others.email'),
                'full_name' => trans('others.full_name'),
                'profile_picture' => trans('others.profile_picture'),
            ];
        }

        return [
            'email' => trans('others.email'),
            'username' => trans('others.username'),
            'password' => trans('others.password')
        ];
    }
}
