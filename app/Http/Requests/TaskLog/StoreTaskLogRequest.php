<?php

namespace App\Http\Requests\TaskLog;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit_tasks');
    }

    public function rules(): array
    {
        return [
            'task_id' => ['required', 'uuid', 'exists:tasks,id'],
            'employee_id' => ['required', 'uuid', 'exists:employees,id'],
            'activity_note' => ['nullable', 'string'],
            'photo_before' => ['nullable', 'string'],
            'photo_after' => ['nullable', 'string'],
        ];
    }
}
