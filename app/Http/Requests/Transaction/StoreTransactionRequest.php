<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create_transactions');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_date' => 'required|date',
            'transaction_type' => 'required|in:Income,Expense',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'reference_type' => 'nullable|string',
            'reference_id' => 'nullable|uuid',
            'payment_method' => 'required|in:Cash,Bank Transfer,Credit Card,Debit Card,Cheque,Other',
            'description' => 'required|string',
            'receipt_path' => 'nullable|string',
        ];
    }
}
