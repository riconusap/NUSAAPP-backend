<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteResource extends JsonResource
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
            'client_id' => $this->client_id,
            'client' => new ClientResource($this->whenLoaded('client')),
            'site_name' => $this->site_name,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'radius_meters' => $this->radius_meters,
            'areas' => AreaResource::collection($this->whenLoaded('areas')),
            'contracts' => EmployeeContractResource::collection($this->whenLoaded('contracts')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
