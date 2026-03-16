<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeContractResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
            'site_id' => $this->site_id,
            'site' => $this->whenLoaded('site', function () {
                return [
                    'id' => $this->site->id,
                    'site_name' => $this->site->site_name,
                    'address' => $this->site->address,
                ];
            }),
            'internal_contract_number' => $this->internal_contract_number,
            'position' => $this->position,
            'salary_type' => $this->salary_type,
            'base_salary' => $this->base_salary,
            'daily_rate' => $this->daily_rate,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'contract_type' => $this->contract_type,
            'is_active' => $this->isActive(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
