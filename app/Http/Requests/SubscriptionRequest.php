<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
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
            'type' => 'required|in:league,competition',
            'league' => 'required_if:type,=,league|integer',
            'season' => 'required_if:type,=,league|integer',
            'competition' => 'required_if:type,=,competition|integer',
        ];
    }

    public function attributes()
    {
        return [
            'type' => trans('others.type'),
            'league' => trans('others.league'),
            'season' => trans('others.season'),
            'competition' => trans('others.competition'),
        ];
    }
}
