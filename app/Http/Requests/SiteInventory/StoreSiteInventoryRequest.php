<?php

namespace App\Http\Requests\SiteInventory;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiteInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_inventory');
    }

    public function rules(): array
    {
        return [
            'site_id' => ['required', 'uuid', 'exists:sites,id'],
            'inventory_item_id' => ['required', 'uuid', 'exists:inventory_items,id'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
        ];
    }
}
