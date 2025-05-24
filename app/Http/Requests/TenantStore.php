<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TenantStore extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            // 'g-recaptcha-response' => ['required', 'string', 'recaptcha'],
            // recaptchaFieldName() => recaptchaRuleName(),

            'name' => ['required', 'string'],
            'subdomain' => [
                'required', 'string',
                'regex:/^[A-Za-z0-9]+(?:[-][A-Za-z0-9]+)*$/',
                Rule::notIn(config('multitenancy.reserved_subdomains')),
                'unique:landlord.tenants',
            ],

            'plan_id' => [
                // 'sometimes',
                'required', 'integer',
                // 'exists:plans,id',
                Rule::exists('landlord.plans', 'id')->where(function ($query) {
                    return $query->where('account_type_id', (int) request('account_type_id'));
                }),
            ],
            'account_type_id' => ['required', 'integer', 'exists:landlord.account_types,id'],

            'phone_prefix' => ['required', 'string'],
            'phone' => ['required', 'string'],

            'email' => ['required', 'string', 'email', 'unique:landlord.tenants'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages()
    {
        return [
            'g-recaptcha-response.*' => 'يرجي الضغط علي "أنا لست برنامج روبوت".',

            'name.*' => 'يرجي إدخال إسم المتجر.',

            'subdomain.unique' => 'هذا الرابط موجود بالفعل.',
            'subdomain.not_in' => 'هذا الرابط موجود بالفعل.',
            'subdomain.*' => 'يرجي إدخال رابط المتجر. مثال: your-best-gift',

            'email.unique' => 'هذا البريد الإلكتروني مشترك بالفعل.',
            'email.*' => 'يرجي إدخال بريد إلكتروني صحيح.',

            'phone.*' => 'يرجي إدخال رقم التليفون.',

            'password.*' => 'يرجي إدخال كلمة مرور لا تقل عن 8 حروف.',

            'plan_id.*' => 'يرجي إختيار نوع الباقة.',
            'account_type_id.*' => 'يرجي إختيار نوع النشاط.',
        ];
    }
}
