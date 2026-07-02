<?php

namespace App\Actions;

use App\Models\Link;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;
use RuntimeException;

class CreateShortLink
{
    private const CODE_LENGTH = 6;

    private const MAX_ATTEMPTS = 5;

    public function execute(User $user, string $originalUrl): Link
    {
        for ($attempt = 0; $attempt < self::MAX_ATTEMPTS; $attempt++) {
            try {
                return $user->links()->create([
                    'original_url' => $originalUrl,
                    'short_code' => $this->generateCode(),
                ]);
            } catch (UniqueConstraintViolationException $exception) {
                if ($attempt === self::MAX_ATTEMPTS - 1) {
                    throw $exception;
                }
            }
        }

        throw new RuntimeException('Unable to create a unique short link.');
    }

    private function generateCode(): string
    {
        return Str::random(self::CODE_LENGTH);
    }
}
