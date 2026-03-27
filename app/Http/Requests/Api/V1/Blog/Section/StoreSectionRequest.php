<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Blog\Section;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class StoreSectionRequest extends FormRequest
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
            'title'               => ['required', 'string', 'max:255'],
            'publishDate'         => ['required', 'date_format:Y-m-d'],
            'description'         => ['nullable', 'string', 'max:1000'],
            'content'             => ['nullable', 'string'],
            'heroImageUrl'        => ['nullable', 'string', 'url', 'max:500'],
            'creators'            => ['present', 'array'],
            'creators.*.name'     => ['required_with:creators', 'string', 'max:255'],
            'creators.*.photoUrl' => ['nullable', 'string', 'url', 'max:500'],
            'sortOrder'           => ['required', 'integer'],
        ];
    }
}
