<?php

namespace Modules\Apps\Http\Requests\Dashboard\Install;

use Illuminate\Foundation\Http\FormRequest;

class SaveCountrySetupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'default_country' => 'required|exists:countries,id',
            'default_currency' => 'required|exists:currencies,code',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
