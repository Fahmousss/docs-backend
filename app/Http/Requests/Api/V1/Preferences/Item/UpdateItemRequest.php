<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Preferences\Item;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<mixed>|string|ValidationRule>
     */
    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'url'       => ['required', 'string', 'url', 'max:500'],
            'imageUrl'  => ['nullable', 'string', 'url', 'max:500'],
            'icon'      => ['nullable', 'string', 'max:50'],
            'content'   => ['nullable', 'string'],
            'sortOrder' => ['required', 'integer'],
        ];
    }
}
