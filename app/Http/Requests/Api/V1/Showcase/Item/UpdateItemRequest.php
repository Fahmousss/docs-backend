<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Showcase\Item;

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
            'thumbnailUrl'     => ['required', 'string', 'url', 'max:500'],
            'title'            => ['required', 'string', 'max:255'],
            'shortDescription' => ['required', 'string', 'max:500'],
            'content'          => ['nullable', 'string'],
            'sortOrder'        => ['required', 'integer'],
        ];
    }
}
