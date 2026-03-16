<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'nik' => $this->nik,
            'nip' => $this->nip,
            'full_name' => $this->full_name,
            'profile_picture_path' => $this->profile_picture_path,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'current_address' => $this->current_address,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'employment_status' => $this->employment_status,
            'user' => new UserResource($this->whenLoaded('user')),
            'contracts' => EmployeeContractResource::collection($this->whenLoaded('contracts')),
            'documents' => EmployeeDocumentResource::collection($this->whenLoaded('documents')),
            'leave_requests' => LeaveRequestResource::collection($this->whenLoaded('leaveRequests')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
