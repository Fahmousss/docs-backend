<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Documentation\Submenu;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateSubmenuRequest extends FormRequest
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
            'content'   => ['nullable', 'string'],
            'sortOrder' => ['required', 'integer'],
        ];
    }
}
