<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $email
 * @property string $password
 */
final class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array|string|ValidationRule>
     */
    public function rules(): array
    {
        return [
            /*
             * @example "test@mail.com"
             */
            'email' => ['required', 'string', 'email'],
            /*
             * @example "password"
             */
            'password' => ['required', 'string'],
        ];
    }
}
