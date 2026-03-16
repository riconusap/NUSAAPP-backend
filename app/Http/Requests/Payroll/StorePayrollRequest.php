<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class StorePayrollRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create_payrolls');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => 'required|uuid|exists:employees,id',
            'period_month' => 'required|integer|min:1|max:12',
            'period_year' => 'required|integer|min:2020|max:2100',
            'basic_salary' => 'required|numeric|min:0',
            'total_days_worked' => 'nullable|integer|min:0',
            'total_hours_worked' => 'nullable|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:Draft,Approved,Paid',
            'notes' => 'nullable|string',
        ];
    }
}
