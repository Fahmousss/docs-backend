<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Documentation;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property array<int, mixed> $sections
 */
final class UpdateDocumentationRequest extends FormRequest
{
    /**
     * @return array<string, array<mixed>|string|ValidationRule>
     */
    public function rules(): array
    {
        return [
            'sections'                                => ['present', 'array'],
            'sections.*.id'                           => ['required', 'string', 'uuid'],
            'sections.*.name'                         => ['required', 'string', 'max:255'],
            'sections.*.sortOrder'                    => ['required', 'integer', 'min:0'],
            'sections.*.menus'                        => ['required', 'array'],
            'sections.*.menus.*.id'                   => ['required', 'string', 'uuid'],
            'sections.*.menus.*.name'                 => ['required', 'string', 'max:255'],
            'sections.*.menus.*.sortOrder'            => ['required', 'integer', 'min:0'],
            'sections.*.menus.*.submenus'             => ['required', 'array'],
            'sections.*.menus.*.submenus.*.id'        => ['required', 'string', 'uuid'],
            'sections.*.menus.*.submenus.*.name'      => ['required', 'string', 'max:255'],
            'sections.*.menus.*.submenus.*.content'   => ['nullable', 'string'],
            'sections.*.menus.*.submenus.*.sortOrder' => ['required', 'integer', 'min:0'],
        ];
    }
}
