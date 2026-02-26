<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Blog;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateBlogRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|string|ValidationRule>
     */
    public function rules(): array
    {
        return [
            'sections'                       => ['present', 'array'],
            'sections.*.id'                  => ['nullable', 'string', 'uuid'],
            'sections.*.title'               => ['required', 'string', 'max:255'],
            'sections.*.publishDate'         => ['required', 'date'],
            'sections.*.description'         => ['nullable', 'string', 'max:1000'],
            'sections.*.content'             => ['nullable', 'string', 'max:65535'],
            'sections.*.heroImageUrl'        => ['nullable', 'string', 'url', 'max:2048'],
            'sections.*.creators'            => ['present', 'array'],
            'sections.*.creators.*.name'     => ['required', 'string', 'max:255'],
            'sections.*.creators.*.photoUrl' => ['nullable', 'string', 'url', 'max:2048'],
            'sections.*.sortOrder'           => ['required', 'integer', 'min:0'],
        ];
    }
}
