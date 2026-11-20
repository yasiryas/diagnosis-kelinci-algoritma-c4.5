<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

final class PasswordHasherTest
{
    public function testVerifiesMythAuthPrehashedPassword(): void
    {
        $hash = App\Services\PasswordHasher::hash('oldpassword');

        expectTrue(App\Services\PasswordHasher::verify('oldpassword', $hash), 'Myth Auth hash must verify');
    }

    public function testVerifiesLegacyPlainPasswordHash(): void
    {
        $hash = password_hash('oldpassword', PASSWORD_DEFAULT);

        expectTrue(App\Services\PasswordHasher::verify('oldpassword', $hash), 'Legacy hash must still verify');
    }

    public function testRejectsWrongPassword(): void
    {
        $hash = App\Services\PasswordHasher::hash('oldpassword');

        expectTrue(!App\Services\PasswordHasher::verify('wrongpassword', $hash), 'Wrong password must be rejected');
    }
}
