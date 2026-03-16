<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit_employees');
    }

    public function rules(): array
    {
        $employee = $this->route('employee');
        $employeeId = $employee ? $employee->id : null;

        return [
            'nik' => ['sometimes', 'string', 'unique:employees,nik,' . $employeeId, 'max:50'],
            'nip' => ['sometimes', 'string', 'unique:employees,nip,' . $employeeId, 'max:50'],
            'full_name' => ['sometimes', 'string', 'max:255'],
            'profile_picture_path' => ['nullable', 'string'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'current_address' => ['nullable', 'string'],
            'birth_date' => ['nullable', 'date'],
            'employment_status' => ['nullable', 'string', 'max:50'],
        ];
    }
}
