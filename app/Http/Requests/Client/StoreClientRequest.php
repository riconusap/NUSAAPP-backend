<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_clients');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'string'],
            'headquarter_address' => ['nullable', 'string'],
            'pic_name' => ['nullable', 'string', 'max:255'],
            'pic_phone' => ['nullable', 'string', 'max:20'],
        ];
    }
}
