<?php

namespace Modules\Package\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class SuspensionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rule =  [
            "subscription_id" => "required|exists:subscriptions,id",
            "start_at"        => "required|date|after_or_equal:today",
            "end_at"          => "required|required|date|gte:start_at",
        ];
        return $rule;
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
