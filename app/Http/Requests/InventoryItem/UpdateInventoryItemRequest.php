<?php

namespace App\Http\Requests\InventoryItem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit_inventory');
    }

    public function rules(): array
    {
        return [
            'item_name' => ['sometimes', 'string', 'max:255'],
            'category' => ['sometimes', 'in:Tool,Material,Fertilizer,Chemical'],
            'unit' => ['sometimes', 'in:pcs,kg,liter,zak'],
            'is_consumable' => ['nullable', 'boolean'],
        ];
    }
}
