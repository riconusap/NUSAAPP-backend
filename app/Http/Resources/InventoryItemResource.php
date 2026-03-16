<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryItemResource extends JsonResource
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
            'item_name' => $this->item_name,
            'category' => $this->category,
            'unit' => $this->unit,
            'is_consumable' => $this->is_consumable,
            'total_stock' => $this->when($this->relationLoaded('siteInventories'), fn() => $this->total_stock),
            'site_inventories' => SiteInventoryResource::collection($this->whenLoaded('siteInventories')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
