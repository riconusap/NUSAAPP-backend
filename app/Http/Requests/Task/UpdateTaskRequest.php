<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit_tasks');
    }

    public function rules(): array
    {
        return [
            'area_id' => ['sometimes', 'uuid', 'exists:areas,id'],
            'assigned_to_id' => ['nullable', 'uuid', 'exists:employees,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'task_type' => ['sometimes', 'in:Daily,Weekly,Monthly,Yearly,Accidental'],
            'priority' => ['nullable', 'in:Low,Medium,High,Urgent'],
            'status' => ['nullable', 'in:To Do,In Progress,Review,Completed'],
            'due_date' => ['nullable', 'date'],
        ];
    }
}
