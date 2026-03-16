<?php

namespace App\Http\Requests\InvoicePlan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoicePlanRequest extends FormRequest
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
            'invoice_schedule' => 'sometimes|in:Monthly,Quarterly,Yearly,One-time',
            'amount_per_invoice' => 'sometimes|numeric|min:0',
            'tax_percentage' => 'sometimes|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ];
    }
}
