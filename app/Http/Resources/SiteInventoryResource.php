<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteInventoryResource extends JsonResource
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
            'inventory_item_id' => $this->inventory_item_id,
            'inventory_item' => new InventoryItemResource($this->whenLoaded('inventoryItem')),
            'stock_quantity' => $this->stock_quantity,
            'is_low_stock' => $this->isLowStock(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
