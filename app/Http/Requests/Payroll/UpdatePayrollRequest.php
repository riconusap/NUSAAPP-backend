<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePayrollRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit_payrolls');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'basic_salary' => 'sometimes|numeric|min:0',
            'total_days_worked' => 'sometimes|integer|min:0',
            'total_hours_worked' => 'sometimes|numeric|min:0',
            'overtime_hours' => 'sometimes|numeric|min:0',
            'overtime_pay' => 'sometimes|numeric|min:0',
            'allowances' => 'sometimes|numeric|min:0',
            'deductions' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:Draft,Approved,Paid',
            'notes' => 'nullable|string',
        ];
    }
}
