<?php

namespace App\Http\Requests\Area;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit_sites');
    }

    public function rules(): array
    {
        return [
            'site_id' => ['sometimes', 'uuid', 'exists:sites,id'],
            'area_name' => ['sometimes', 'string', 'max:255'],
            'surface_area_m2' => ['nullable', 'numeric', 'min:0'],
            'current_condition_image' => ['nullable', 'string'],
        ];
    }
}
