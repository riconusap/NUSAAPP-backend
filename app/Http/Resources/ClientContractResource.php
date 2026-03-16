<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientContractResource extends JsonResource
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
            'contract_number' => $this->contract_number,
            'contract_type' => $this->contract_type,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'total_contract_value' => $this->total_contract_value,
            'is_active' => $this->isActive(),
            // 'invoice_plans' => $this->whenLoaded('invoicePlans'), // TODO: Add when InvoicePlan model is created
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
