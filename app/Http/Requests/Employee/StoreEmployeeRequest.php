<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_employees');
    }

    public function rules(): array
    {
        return [
            'site_id' => ['required', 'integer', 'exists:sites,id'],
            'nik' => ['required', 'string', 'unique:employees,nik', 'max:50'],
            'nip' => ['prohibited'],
            'full_name' => ['required', 'string', 'max:255'],
            'profile_picture_path' => ['nullable', 'string'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'current_address' => ['nullable', 'string'],
            'birth_date' => ['nullable', 'date'],
            'employment_status' => ['nullable', 'string', 'max:50'],
        ];
    }
}
