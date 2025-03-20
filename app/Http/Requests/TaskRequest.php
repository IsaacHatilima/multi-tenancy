<?php

namespace App\Http\Requests;

use App\Enums\TaskEscalation;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'assigned_to' => ['required', 'exists:users,id'],
            'priority' => ['required', Rule::in(array_column(TaskPriority::cases(), 'value'))],
            'escalation' => ['nullable', Rule::in(array_column(TaskEscalation::cases(), 'value'))],
            'status' => ['required', Rule::in(array_column(TaskStatus::cases(), 'value'))],
            'title' => ['required'],
            'description' => ['required'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
