<?php

namespace App\Http\Requests\ClientContract;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_contracts');
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'uuid', 'exists:clients,id'],
            'contract_number' => ['required', 'string', 'unique:client_contracts,contract_number', 'max:255'],
            'contract_type' => ['required', 'in:Monthly_Retainer,Project_Based'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'total_contract_value' => ['required', 'numeric', 'min:0'],
        ];
    }
}
