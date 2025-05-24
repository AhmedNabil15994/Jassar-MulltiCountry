<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Subdomain extends FormRequest
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
            'subdomain' => [
                'required', 'string',
                'regex:/^[A-Za-z0-9]+(?:[-][A-Za-z0-9]+)*$/',
                Rule::notIn(config('multitenancy.reserved_subdomains')),
                'unique:landlord.tenants',
            ],
        ];
    }

    public function messages()
    {
        return [
            'subdomain.unique' => 'هذا الرابط موجود بالفعل.',
            'subdomain.not_in' => 'هذا الرابط موجود بالفعل.',
            'subdomain.*' => 'يرجي إدخال رابط المتجر. مثال: your-best-gift',
        ];
    }
}
