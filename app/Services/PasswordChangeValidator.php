<?php

namespace App\Services;

class PasswordChangeValidator
{
    private const MINIMUM_LENGTH = 8;

    public function validate(string $currentHash, string $current, string $new, string $confirmation): array
    {
        $errors = [];

        if ($currentHash === '' || !PasswordHasher::verify($current, $currentHash)) {
            $errors['current'] = 'Password lama tidak sesuai';
        }

        if (strlen($new) < self::MINIMUM_LENGTH) {
            $errors['new'] = 'Password baru minimal 8 karakter';
        }

        if ($new !== $confirmation) {
            $errors['confirm'] = 'Konfirmasi password tidak sesuai';
        }

        return $errors;
    }
}
