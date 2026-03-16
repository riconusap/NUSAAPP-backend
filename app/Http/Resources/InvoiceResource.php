<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'invoice_plan_id' => $this->invoice_plan_id,
            'invoice_plan' => $this->whenLoaded('invoicePlan', function () {
                return [
                    'id' => $this->invoicePlan->id,
                    'invoice_schedule' => $this->invoicePlan->invoice_schedule,
                ];
            }),
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
            'invoice_number' => $this->invoice_number,
            'invoice_date' => $this->invoice_date->format('Y-m-d'),
            'due_date' => $this->due_date->format('Y-m-d'),
            'amount' => (float) $this->amount,
            'tax_amount' => (float) $this->tax_amount,
            'total_amount' => (float) $this->total_amount,
            'status' => $this->status,
            'is_overdue' => $this->isOverdue(),
            'paid_at' => $this->paid_at?->format('Y-m-d H:i:s'),
            'payment_method' => $this->payment_method,
            'attachments' => $this->whenLoaded('attachments', function () {
                return InvoiceAttachmentResource::collection($this->attachments);
            }),
            'notes' => $this->notes,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
