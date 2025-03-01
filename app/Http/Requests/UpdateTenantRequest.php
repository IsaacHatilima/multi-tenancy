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
                'zip' => ['required', 'numeric', 'digits_between:3,10'],
                'contact_phone' => ['required', 'regex:/^(?:\+?\d{1,4}[\s\-]?)?(\(?\d{2,5}\)?[\s\-]?)?\d{7,15}$/', 'min:7', 'max:50'],
            ],
            StringRule::rules('address', true),
            StringRule::rules('city', true),
            StringRule::rules('state', true),
            StringRule::rules('country', true),
            StringRule::rules('contact_first_name', true),
            StringRule::rules('contact_last_name', true),
            StringRule::rules('contact_email', true),
            StringRule::rules('status', true),
        );
    }

    public function messages(): array
    {
        return (new TenantRequest)->messages();
    }

    public function authorize(): bool
    {
        return true;
    }
}
