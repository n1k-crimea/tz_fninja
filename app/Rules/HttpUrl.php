<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HttpUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || filter_var($value, FILTER_VALIDATE_URL) === false) {
            $fail('Укажите корректный URL.');

            return;
        }

        $scheme = parse_url($value, PHP_URL_SCHEME);

        if (! in_array($scheme, ['http', 'https'], true)) {
            $fail('URL должен начинаться с http:// или https://.');
        }
    }
}
