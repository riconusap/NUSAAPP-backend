<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class AssignTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('assign_tasks');
    }

    public function rules(): array
    {
        return [
            'assigned_to_id' => ['required', 'uuid', 'exists:employees,id'],
        ];
    }
}
