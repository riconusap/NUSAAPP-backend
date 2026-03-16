<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoicePlanResource extends JsonResource
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
            'client_contract_id' => $this->client_contract_id,
            'client_contract' => $this->whenLoaded('clientContract', function () {
                return [
                    'id' => $this->clientContract->id,
                    'contract_number' => $this->clientContract->contract_number,
                    'client' => $this->whenLoaded('clientContract.client', function () {
                        return [
                            'id' => $this->clientContract->client->id,
                            'client_name' => $this->clientContract->client->client_name,
                        ];
                    }),
                ];
            }),
            'invoice_schedule' => $this->invoice_schedule,
            'amount_per_invoice' => (float) $this->amount_per_invoice,
            'tax_percentage' => (float) $this->tax_percentage,
            'tax_amount' => (float) $this->tax_amount,
            'total_amount' => (float) $this->total_amount,
            'invoices_count' => $this->whenLoaded('invoices', function () {
                return $this->invoices->count();
            }),
            'notes' => $this->notes,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
