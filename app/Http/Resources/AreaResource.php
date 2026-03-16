<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaResource extends JsonResource
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
            'site_id' => $this->site_id,
            'site' => new SiteResource($this->whenLoaded('site')),
            'area_name' => $this->area_name,
            'surface_area_m2' => $this->surface_area_m2,
            'current_condition_image' => $this->current_condition_image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
