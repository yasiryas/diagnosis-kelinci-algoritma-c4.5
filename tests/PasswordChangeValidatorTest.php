<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

final class PasswordChangeValidatorTest
{
    public function testAcceptsValidChange(): void
    {
        $hash = App\Services\PasswordHasher::hash('oldpassword');
        $validator = new App\Services\PasswordChangeValidator();

        $errors = $validator->validate($hash, 'oldpassword', 'newpassword', 'newpassword');

        expectSame([], $errors, 'Valid password change must have no errors');
    }

    public function testRejectsWrongCurrentPassword(): void
    {
        $hash = App\Services\PasswordHasher::hash('oldpassword');
        $validator = new App\Services\PasswordChangeValidator();

        $errors = $validator->validate($hash, 'wrongpassword', 'newpassword', 'newpassword');

        expectSame('Password lama tidak sesuai', $errors['current'] ?? null, 'Wrong current password must be reported');
    }

    public function testRejectsMismatchedConfirmation(): void
    {
        $hash = App\Services\PasswordHasher::hash('oldpassword');
        $validator = new App\Services\PasswordChangeValidator();

        $errors = $validator->validate($hash, 'oldpassword', 'newpassword', 'differentpassword');

        expectSame('Konfirmasi password tidak sesuai', $errors['confirm'] ?? null, 'Mismatch must be reported');
    }

    public function testRejectsShortNewPassword(): void
    {
        $hash = App\Services\PasswordHasher::hash('oldpassword');
        $validator = new App\Services\PasswordChangeValidator();

        $errors = $validator->validate($hash, 'oldpassword', 'short', 'short');

        expectSame('Password baru minimal 8 karakter', $errors['new'] ?? null, 'Short password must be reported');
    }
}
