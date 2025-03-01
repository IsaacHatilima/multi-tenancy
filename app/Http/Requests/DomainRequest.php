<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DomainRequest extends FormRequest
{
    public function rules(): array
    {
        $domain = $this->route('domain'); // This is an instance of Domain

        return [
            'domain' => [
                'required',
                'min:3',
                'max:10',
                'unique:domains,domain,'.$domain->id,
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'domain.required' => 'Subdomain is required',
            'domain.min' => 'Subdomain must be at least 3 characters',
            'domain.max' => 'Subdomain must be at most 10 characters',
            'domain.unique' => 'Subdomain already exists',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
