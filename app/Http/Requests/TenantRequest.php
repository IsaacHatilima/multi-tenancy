<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TenantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:255', 'unique:tenants'],
            'address' => ['required', 'min:3', 'max:255'],
            'city' => ['required', 'min:3', 'max:255'],
            'state' => ['required', 'min:3', 'max:255'],
            'country' => ['required', 'min:3', 'max:255'],
            'zip' => ['required', 'min:3', 'max:255', 'numeric'],
            'contact_name' => ['required', 'min:3', 'max:255'],
            'contact_email' => ['required', 'min:3', 'max:255'],
            'contact_phone' => ['required', 'min:3', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
