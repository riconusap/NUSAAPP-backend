<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit_invoices');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invoice_date' => 'sometimes|date',
            'due_date' => 'sometimes|date|after_or_equal:invoice_date',
            'amount' => 'sometimes|numeric|min:0',
            'tax_amount' => 'sometimes|numeric|min:0',
            'total_amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:Draft,Sent,Paid,Overdue,Cancelled',
            'notes' => 'nullable|string',
        ];
    }
}
