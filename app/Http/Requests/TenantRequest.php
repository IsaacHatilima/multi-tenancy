<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TenantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'data' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
