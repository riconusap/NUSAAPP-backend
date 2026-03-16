<?php

namespace App\Http\Requests\ClientContract;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit_contracts');
    }

    public function rules(): array
    {
        $contract = $this->route('client_contract');
        $contractId = $contract ? $contract->id : null;

        return [
            'client_id' => ['sometimes', 'uuid', 'exists:clients,id'],
            'contract_number' => ['sometimes', 'string', 'unique:client_contracts,contract_number,' . $contractId, 'max:255'],
            'contract_type' => ['sometimes', 'in:Monthly_Retainer,Project_Based'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'total_contract_value' => ['sometimes', 'numeric', 'min:0'],
        ];
    }
}
