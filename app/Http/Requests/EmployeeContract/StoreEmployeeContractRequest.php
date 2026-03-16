<?php

namespace App\Http\Requests\EmployeeContract;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_contracts');
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'uuid', 'exists:employees,id'],
            'site_id' => ['nullable', 'uuid', 'exists:sites,id'],
            'internal_contract_number' => ['required', 'string', 'unique:employee_contracts,internal_contract_number'],
            'position' => ['required', 'string', 'max:100'],
            'salary_type' => ['required', 'in:Monthly,Daily'],
            'base_salary' => ['required_if:salary_type,Monthly', 'numeric', 'min:0'],
            'daily_rate' => ['required_if:salary_type,Daily', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'contract_type' => ['nullable', 'string', 'max:50'],
        ];
    }
}
