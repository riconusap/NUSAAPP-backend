<?php

namespace App\Http\Requests\EmployeeContract;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit_contracts');
    }

    public function rules(): array
    {
        $contract = $this->route('employee_contract');
        $contractId = $contract ? $contract->id : null;

        return [
            'employee_id' => ['sometimes', 'uuid', 'exists:employees,id'],
            'site_id' => ['nullable', 'uuid', 'exists:sites,id'],
            'internal_contract_number' => ['sometimes', 'string', 'unique:employee_contracts,internal_contract_number,' . $contractId],
            'position' => ['sometimes', 'string', 'max:100'],
            'salary_type' => ['sometimes', 'in:Monthly,Daily'],
            'base_salary' => ['sometimes', 'numeric', 'min:0'],
            'daily_rate' => ['sometimes', 'numeric', 'min:0'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'contract_type' => ['nullable', 'string', 'max:50'],
        ];
    }
}
