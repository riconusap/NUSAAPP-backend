<?php

namespace App\Http\Requests\SiteInventory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit_inventory');
    }

    public function rules(): array
    {
        return [
            'stock_quantity' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
