<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_sites');
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'uuid', 'exists:clients,id'],
            'site_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'radius_meters' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
