<?php

namespace Modules\Apps\Http\Requests\Dashboard\Install;

use Illuminate\Foundation\Http\FormRequest;

class SaveGeneralInfoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'app_name.*' => 'required',
            'contact_us.email' => 'required',
            'contact_us.whatsapp' => 'required',
            'contact_us.mobile' => 'required',
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
