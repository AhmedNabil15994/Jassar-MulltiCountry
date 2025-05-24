<?php

namespace Modules\Package\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rule = [
            "title" => "required",
            // "title.*" => "required|unique_translation:packages,title|string|max:255",
            "description" => "nullable",
            "qty" => "required",
            "free_qty" => "required",
            "image" => "required|image|max:4028",
            "country_id" => "required",
            "settings.products" => "required",
            "settings.free_products" => "required",
            "status" => "sometimes",
        ];

        if ($this->isMethod('PUT')) {
            // $rule["title.*"] = "required|unique_translation:packages,title,{$this->package},id|string|max:255";
            $rule["image"] = "sometimes|image|max:4028";
        }

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
