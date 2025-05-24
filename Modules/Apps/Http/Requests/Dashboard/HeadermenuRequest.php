<?php

namespace Modules\Apps\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class HeadermenuRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->has('type')){
            return config('menu.add_items_forms')[$this->type]['validation'];
        }else{
            return ['type' => 'required'];
        }
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
