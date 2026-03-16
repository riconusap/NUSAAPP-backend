<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'invoice_plan_id' => 'nullable|uuid|exists:invoice_plans,id',
            'client_contract_id' => 'required|uuid|exists:client_contracts,id',
            'invoice_number' => 'nullable|string|unique:invoices,invoice_number',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'nullable|in:Draft,Sent,Paid,Overdue,Cancelled',
            'notes' => 'nullable|string',
        ];
    }
}
