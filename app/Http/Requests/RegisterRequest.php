<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class RegisterRequest extends FormRequest
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
        if(User::where('email', $this->email)->first()){
            print_r(json_encode(["error" => "account already exist with this email"])); exit;
        }

        if($this->has('unique_id')) {
            return [
                'unique_id' => 'required|string|unique:users',
                'username' => 'required|string|max:255',
                'full_name' => 'nullable|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'country' => 'required|integer'
            ];
        }
        
        return [
            'username' => 'required|string|max:255|not_in:super-admin,public-user|unique:users',
            'full_name' => 'nullable|string|max:255',
            'country' => 'required|integer',
            'email' => 'required|string|email|max:255|unique:users',
            'dob' => 'nullable|date|date_format:Y-m-d',
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms_of_service' => 'accepted'
        ];
    }

    public function attributes()
    {
        return [
            'username' => trans('others.username'),
            'full_name' => trans('others.full_name'),
            'country' => trans('others.country'),
            'email' => trans('others.email'),
            'dob' => trans('others.dob'),
            'password' => trans('others.password'),
            'terms_of_service' => trans('others.terms_of_service')
        ];
    }
}
