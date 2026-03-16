<?php

namespace App\Http\Requests\InvoicePlan;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoicePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create_invoices');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_contract_id' => 'required|uuid|exists:client_contracts,id',
            'invoice_schedule' => 'required|in:Monthly,Quarterly,Yearly,One-time',
            'amount_per_invoice' => 'required|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ];
    }
}
