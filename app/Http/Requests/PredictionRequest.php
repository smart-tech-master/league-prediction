<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PredictionRequest extends FormRequest
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

        if($this->filled('predictions')){
            foreach ($this->predictions as $key => $values){
                if(isset($values['goals']['home']) && isset($values['goals']['away'])) {
                    if (filled($values['goals']['home']) && filled($values['goals']['away'])) {
                    } elseif (!filled($values['goals']['home']) && !filled($values['goals']['away'])) {
                    } else {
                        $rules = array_merge($rules, [
                            'predictions.' . $key . '.goals.home' => 'required|in:0,1,2,3,4,5,6,7,8,9',
                            'predictions.' . $key . '.goals.away' => 'required|in:0,1,2,3,4,5,6,7,8,9',
                        ]);
                    }
                }
            }
        }

        return $rules;
    }
}
