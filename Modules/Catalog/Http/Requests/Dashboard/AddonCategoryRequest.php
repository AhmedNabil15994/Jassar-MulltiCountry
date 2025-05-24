<?php

namespace Modules\Catalog\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class AddonCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        return [
            'title.*' => 'required',
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

    public function messages()
    {
        $v = [
            // 'image.required' => __('catalog::dashboard.categories.validation.image.required'),
            'image.required' => __('apps::dashboard.validation.image.required'),
            'image.image' => __('apps::dashboard.validation.image.image'),
            'image.mimes' => __('apps::dashboard.validation.image.mimes') . ': ' . config('core.config.image_mimes'),
            'image.max' => __('apps::dashboard.validation.image.max') . ': ' . config('core.config.image_max'),
        ];
        foreach (config('laravellocalization.supportedLocales') as $key => $value) {
            $v["title." . $key . ".required"] = __('catalog::dashboard.addon_categories.validation.title.required') . ' - ' . $value['native'] . '';
            $v["title." . $key . ".unique_translation"] = __('catalog::dashboard.addon_categories.validation.title.unique') . ' - ' . $value['native'] . '';
        }
        return $v;
    }
}
