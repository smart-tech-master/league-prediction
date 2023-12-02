<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvailabilityRequest extends FormRequest
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
        $rules = [];

        switch ($this->route()->parameter('col')){
            case 'email':
                $rules = array_merge($rules, [
                    'email' => 'required|email|unique:users'
                ]);
                break;

            case 'username':
                $rules = array_merge($rules, [
                    'username' => 'required|unique:users'
                ]);
                break;
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        switch ($this->route()->parameter('col')){
            case 'email':
                $attributes = array_merge($attributes, [
                    'email' => trans('others.email')
                ]);
                break;

            case 'username':
                $attributes = array_merge($attributes, [
                    'username' => trans('others.username')
                ]);
                break;
        }

        return $attributes;
    }
}
