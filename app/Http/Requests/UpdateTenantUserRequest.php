<?php

namespace App\Http\Requests;

use App\Rules\ExistingEmailRule;
use App\Rules\StringRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTenantUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');

        return array_merge(
            StringRule::rules('first_name', true),
            StringRule::rules('last_name', true),
            ExistingEmailRule::rules($user->id),
        );
    }

    public function messages(): array
    {
        return array_merge(
            StringRule::messages('first_name', true),
            StringRule::messages('last_name', true),
            ExistingEmailRule::messages(),
        );
    }
}
