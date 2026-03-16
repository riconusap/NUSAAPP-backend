<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'transaction_date' => $this->transaction_date->format('Y-m-d'),
            'transaction_type' => $this->transaction_type,
            'category' => $this->category,
            'amount' => (float) $this->amount,
            'reference_type' => $this->reference_type,
            'reference_id' => $this->reference_id,
            'payment_method' => $this->payment_method,
            'description' => $this->description,
            'receipt_path' => $this->receipt_path,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
