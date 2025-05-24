<?php

namespace Modules\DriverApp\Http\Requests\WebService;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'       => 'required',
            /* 'mobile'     => 'nullable|unique:users,mobile|numeric|digits_between:8,8',
            'email'      => 'nullable|email|unique:users,email', */

            'mobile' => [
                'nullable',
                Rule::unique("users")->where(function ($query) {
                    $query->where("mobile", $this->mobile)
                        ->where("calling_code", $this->calling_code ?? '965');
                }),
                'numeric', 'digits_between:3,20'
            ],

            'email' => 'nullable|email|unique:users,email',
            'password'   => 'required|confirmed|min:6',
        ];

        if (empty($this->email) && empty($this->mobile)) {
            $rules['email_or_mobile'] = 'required';
        }

        return $rules;
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
            'name.required'         =>   __('authentication::api.register.validation.name.required'),
            'mobile.required'       =>   __('authentication::api.register.validation.mobile.required'),
            'mobile.unique'         =>   __('authentication::api.register.validation.mobile.unique'),
            'mobile.numeric'        =>   __('authentication::api.register.validation.mobile.numeric'),
            'mobile.digits_between' =>   __('authentication::api.register.validation.mobile.digits_between'),
            'email.required'        =>   __('authentication::api.register.validation.email.required'),
            'email.unique'          =>   __('authentication::api.register.validation.email.unique'),
            'email.email'           =>   __('authentication::api.register.validation.email.email'),
            'password.required'     =>   __('authentication::api.register.validation.password.required'),
            'password.min'          =>   __('authentication::api.register.validation.password.min'),
            'password.confirmed'    =>   __('authentication::api.register.validation.password.confirmed'),
            'email_or_mobile.required' => __('authentication::api.register.validation.email_or_mobile.required'),
        ];

        return $v;
    }

    /* public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (is_null($this->mobile) && is_null($this->email)) {
                return $validator->errors()->add(
                    'email_or_mobile',
                    __('authentication::frontend.register.validation.email_or_mobile.required')
                );
            }
        });
        return true;
    } */
}
