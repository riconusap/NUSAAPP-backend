<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'employee' => $this->whenLoaded('employee', function () {
                return [
                    'id' => $this->employee->id,
                    'nik' => $this->employee->nik,
                    'nip' => $this->employee->nip,
                    'full_name' => $this->employee->full_name,
                ];
            }),
            'period_month' => $this->period_month,
            'period_year' => $this->period_year,
            'period' => date('F Y', mktime(0, 0, 0, $this->period_month, 1, $this->period_year)),
            'basic_salary' => (float) $this->basic_salary,
            'total_days_worked' => $this->total_days_worked,
            'total_hours_worked' => (float) $this->total_hours_worked,
            'overtime_hours' => (float) $this->overtime_hours,
            'overtime_pay' => (float) $this->overtime_pay,
            'allowances' => (float) $this->allowances,
            'deductions' => (float) $this->deductions,
            'gross_salary' => (float) $this->gross_salary,
            'net_salary' => (float) $this->net_salary,
            'status' => $this->status,
            'paid_at' => $this->paid_at?->format('Y-m-d H:i:s'),
            'notes' => $this->notes,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
