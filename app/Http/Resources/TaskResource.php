<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'area_id' => $this->area_id,
            'area' => new AreaResource($this->whenLoaded('area')),
            'assigned_to_id' => $this->assigned_to_id,
            'assigned_to' => new EmployeeResource($this->whenLoaded('assignedTo')),
            'title' => $this->title,
            'description' => $this->description,
            'task_type' => $this->task_type,
            'priority' => $this->priority,
            'status' => $this->status,
            'due_date' => $this->due_date?->format('Y-m-d'),
            'is_overdue' => $this->isOverdue(),
            'logs' => TaskLogResource::collection($this->whenLoaded('logs')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
