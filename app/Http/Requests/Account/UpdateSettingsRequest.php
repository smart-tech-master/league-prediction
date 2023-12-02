<?php

namespace App\Http\Requests\Account;

use App\Models\Locale;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
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
            'l10n' => 'required|in:' . Locale::get()->pluck('code')->implode(','),
            'receive_notifications' => 'required|boolean',
        ];
    }

    public function attributes()
    {
        return [
            'l10n' => trans('10n'),
            'receive_notifications' => trans('receive_notifications'),
        ];
    }
}
