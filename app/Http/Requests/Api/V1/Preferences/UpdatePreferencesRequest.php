<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Preferences;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class UpdatePreferencesRequest extends FormRequest
{
    /**
     * @return array<string, array<mixed>|string|ValidationRule>
     */
    public function rules(): array
    {
        return [
            'sections'                     => ['present', 'array'],
            'sections.*.id'                => ['nullable', 'string', 'uuid'],
            'sections.*.name'              => ['required', 'string', 'max:255'],
            'sections.*.sortOrder'         => ['required', 'integer', 'min:0'],
            'sections.*.items'             => ['required', 'array'],
            'sections.*.items.*.id'        => ['nullable', 'string', 'uuid'],
            'sections.*.items.*.itemName'  => ['required', 'string', 'max:255'],
            'sections.*.items.*.content'   => ['nullable', 'string', 'max:65535'],
            'sections.*.items.*.sortOrder' => ['required', 'integer', 'min:0'],
        ];
    }
}
