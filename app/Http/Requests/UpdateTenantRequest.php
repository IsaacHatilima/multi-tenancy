<?php

namespace App\Http\Requests;

use App\Rules\StringRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTenantRequest extends FormRequest
{
    public function rules(): array
    {
        $id = $this->route('tenant');

        return array_merge(
            [
                'name' => ['required', 'min:3', 'max:50', Rule::unique('tenants')->ignore($id)],
                'zip' => ['required', 'regex:/^\d+$/', 'min:3', 'max:50'],
                'contact_phone' => ['required', 'regex:/^\d+$/', 'min:7', 'max:50'],
            ],
            StringRule::rules('address', true),
            StringRule::rules('city', true),
            StringRule::rules('state', true),
            StringRule::rules('country', true),
            StringRule::rules('contact_name', true),
            StringRule::rules('contact_email', true),
            StringRule::rules('status', true),
        );
    }

    public function messages(): array
    {
        return array_merge(
            StringRule::messages('address', true),
            StringRule::messages('city', true),
            StringRule::messages('state', true),
            StringRule::messages('country', true),
            StringRule::messages('contact name', true),
            StringRule::messages('contact email', true),
            StringRule::messages('status', true),
            [
                'name.required' => 'Tenant Name is required.',
                'name.min' => 'Tenant Name must be at least 3 characters.',
                'name.max' => 'Tenant Name may not be greater than 50 characters.',
                'name.unique' => 'Tenant Name has already been taken.',

                'zip.required' => 'Postal Code is required.',
                'zip.min' => 'Postal Code must be at least 3 characters.',
                'zip.max' => 'Postal Code may not be greater than 50 characters.',
                'zip.integer' => 'Postal Code must be numeric.',

                'contact_phone.required' => 'Phone Number is required.',
                'contact_phone.min' => 'Phone Number must be at least 3 characters.',
                'contact_phone.max' => 'Phone Number may not be greater than 50 characters.',
                'contact_phone.integer' => 'Phone Number must be numeric.',
            ]
        );
    }

    public function authorize(): bool
    {
        return true;
    }
}
