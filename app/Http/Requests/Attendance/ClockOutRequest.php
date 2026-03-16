<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class ClockOutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_attendances');
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'uuid', 'exists:employees,id'],
            'site_id' => ['required', 'uuid', 'exists:sites,id'],
            'date' => ['nullable', 'date'],
            'selfie_path' => ['nullable', 'string'],
        ];
    }
}
