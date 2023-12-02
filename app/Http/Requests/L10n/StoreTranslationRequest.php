<?php

namespace App\Http\Requests\L10n;

use App\Models\Locale;
use Illuminate\Foundation\Http\FormRequest;

class StoreTranslationRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $rules = [
            'key' => 'required|string|max:250|unique:translations',
        ];

        foreach (Locale::all() as $locale){
            $rules = array_merge($rules, [
                'text.' . $locale->code => 'required'
            ]);
        }

        return $rules;
    }

    public function attributes()
    {
        return [];
    }
}
