<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit_sites');
    }

    public function rules(): array
    {
        return [
            'client_id' => ['sometimes', 'uuid', 'exists:clients,id'],
            'site_name' => ['sometimes', 'string', 'max:255'],
            'address' => ['sometimes', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'radius_meters' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
