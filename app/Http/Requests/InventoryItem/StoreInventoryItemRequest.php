<?php

namespace App\Http\Requests\InventoryItem;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_inventory');
    }

    public function rules(): array
    {
        return [
            'item_name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:Tool,Material,Fertilizer,Chemical'],
            'unit' => ['required', 'in:pcs,kg,liter,zak'],
            'is_consumable' => ['nullable', 'boolean'],
        ];
    }
}
