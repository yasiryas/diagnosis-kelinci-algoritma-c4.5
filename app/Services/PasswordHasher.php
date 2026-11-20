<?php

namespace App\Services;

class PasswordHasher
{
    public static function hash(string $password): string
    {
        return password_hash(self::prehash($password), PASSWORD_DEFAULT);
    }

    public static function verify(string $password, string $hash): bool
    {
        return password_verify(self::prehash($password), $hash) || password_verify($password, $hash);
    }

    public static function prehash(string $password): string
    {
        return base64_encode(hash('sha384', $password, true));
    }
}
