<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DomainRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tenant_id' => ['required', 'exists:tenants'],
            'domain' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
