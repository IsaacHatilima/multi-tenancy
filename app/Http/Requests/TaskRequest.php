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

    public function messages(): array
    {
        return [
            'assigned_to.required' => 'Task should be assigned to a user.',
            'assigned_to.exists' => 'The selected assigned user does not exist.',

            'priority.required' => 'Task should be assigned a priority.',
            'priority.in' => 'The selected priority is invalid.',

            'escalation.in' => 'The selected escalation is invalid.',

            'status.required' => 'Task should be assigned a status.',
            'status.in' => 'The selected status is invalid.',

            'title.required' => 'Task should have a title.',
            'description.required' => 'Task should have a description.',

            'start.required' => 'Task should be started date.',
            'start.date' => 'The start date must be a valid date.',

            'end.required' => 'Task should be ended date.',
            'end.date' => 'The end date must be a valid date.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
