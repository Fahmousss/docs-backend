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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            /**
             * @example []
             */
            'sections' => ['present', 'array'],

            'sections.*.id' => ['required', 'string', 'uuid'],

            /**
             * @example "Getting Started"
             */
            'sections.*.name' => ['required', 'string', 'max:255'],

            /**
             * @example 1
             */
            'sections.*.sortOrder' => ['required', 'integer', 'min:0'],

            'sections.*.menus' => ['required', 'array'],

            'sections.*.menus.*.id' => ['required', 'string', 'uuid'],

            /**
             * @example "Introduction"
             */
            'sections.*.menus.*.name' => ['required', 'string', 'max:255'],

            /**
             * @example 1
             */
            'sections.*.menus.*.sortOrder' => ['required', 'integer', 'min:0'],

            'sections.*.menus.*.submenus' => ['required', 'array'],

            'sections.*.menus.*.submenus.*.id' => ['required', 'string', 'uuid'],

            /**
             * @example "Welcome"
             */
            'sections.*.menus.*.submenus.*.name' => ['required', 'string', 'max:255'],

            /**
             * @example "<p>Hello <strong>World</strong></p>"
             */
            'sections.*.menus.*.submenus.*.content' => ['nullable', 'string'],

            /**
             * @example 1
             */
            'sections.*.menus.*.submenus.*.sortOrder' => ['required', 'integer', 'min:0'],
        ];
    }
}
