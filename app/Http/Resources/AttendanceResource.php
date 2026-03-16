<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
            'site_id' => $this->site_id,
            'site' => new SiteResource($this->whenLoaded('site')),
            'date' => $this->date->format('Y-m-d'),
            'clock_in' => $this->clock_in?->format('Y-m-d H:i:s'),
            'clock_out' => $this->clock_out?->format('Y-m-d H:i:s'),
            'latitude_in' => $this->latitude_in,
            'longitude_in' => $this->longitude_in,
            'selfie_path_in' => $this->selfie_path_in,
            'selfie_path_out' => $this->selfie_path_out,
            'status' => $this->status,
            'working_hours' => $this->working_hours,
            'is_within_radius' => $this->when($this->latitude_in && $this->longitude_in, fn() => $this->isWithinSiteRadius()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
