<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Showcase;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property array<int, mixed> $items
 */
final class UpdateShowcaseRequest extends FormRequest
{
    /**
     * @return array<string, array<mixed>|string|ValidationRule>
     */
    public function rules(): array
    {
        return [
            'items'               => ['present', 'array'],
            'items.*.id'          => ['nullable', 'string', 'uuid'],
            'items.*.title'       => ['required', 'string', 'max:255'],
            'items.*.description' => ['nullable', 'string', 'max:255'],
            'items.*.mediaUrl'    => ['nullable', 'string', 'url', 'max:2048'],
            'items.*.content'     => ['nullable', 'string', 'max:65535'],
            'items.*.sortOrder'   => ['required', 'integer', 'min:0'],
        ];
    }
}
