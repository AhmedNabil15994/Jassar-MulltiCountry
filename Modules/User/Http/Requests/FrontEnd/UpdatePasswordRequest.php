<?php

namespace Modules\User\Http\Requests\FrontEnd;

use Hash;
use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Rule\WebService\OldPasswordRule;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password'  => ['required' , new OldPasswordRule],
            'password' => 'required|confirmed|min:6|max:191',
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
        return [
            'password.required' => __('user::frontend.profile.index.validation.password.required'),
            'password.min' => __('user::frontend.profile.index.validation.password.min'),
            'password.confirmed' => __('user::frontend.profile.index.validation.password.confirmed'),
            'password.required_with' => __('user::frontend.profile.index.validation.password.required_with'),
            'current_password.required' => __('user::frontend.profile.index.validation.current_password.required_with'),
        ];
    }
}
