<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_tasks');
    }

    public function rules(): array
    {
        return [
            'area_id' => ['required', 'uuid', 'exists:areas,id'],
            'assigned_to_id' => ['nullable', 'uuid', 'exists:employees,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'task_type' => ['required', 'in:Daily,Weekly,Monthly,Yearly,Accidental'],
            'priority' => ['nullable', 'in:Low,Medium,High,Urgent'],
            'status' => ['nullable', 'in:To Do,In Progress,Review,Completed'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }
}
