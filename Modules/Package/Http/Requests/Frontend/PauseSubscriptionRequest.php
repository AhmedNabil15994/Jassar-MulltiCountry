<?php

namespace Modules\Package\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class PauseSubscriptionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return     [
            'pause_start_at'               => 'required|date',
            'pause_end_at'             => 'required|date',
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
