<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskLogResource extends JsonResource
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
            'task_id' => $this->task_id,
            'task' => new TaskResource($this->whenLoaded('task')),
            'employee_id' => $this->employee_id,
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
            'activity_note' => $this->activity_note,
            'photo_before' => $this->photo_before,
            'photo_after' => $this->photo_after,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
