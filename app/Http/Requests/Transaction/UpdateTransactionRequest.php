<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit_transactions');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_date' => 'sometimes|date',
            'transaction_type' => 'sometimes|in:Income,Expense',
            'category' => 'sometimes|string|max:255',
            'amount' => 'sometimes|numeric|min:0',
            'payment_method' => 'sometimes|in:Cash,Bank Transfer,Credit Card,Debit Card,Cheque,Other',
            'description' => 'sometimes|string',
            'receipt_path' => 'nullable|string',
        ];
    }
}
