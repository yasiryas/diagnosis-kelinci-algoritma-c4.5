<?php

declare(strict_types=1);

final class ProfileAjaxTest
{
    public function testProfileAndPasswordUseAjaxMarkedModals(): void
    {
        $modal = (string) file_get_contents(APP_ROOT . '/app/Views/templates/modal.php');
        expectTrue(strpos($modal, 'profileModal') !== false, 'Modal profil harus ada.');
        expectTrue(strpos($modal, 'passwordModal') !== false, 'Modal password harus ada.');
    }

    public function testUserUpdateMethodsBranchOnAjax(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/User.php');
        expectTrue(strpos($source, 'function updateProfile') !== false, 'updateProfile harus ada.');
        expectTrue(strpos($source, 'function updatePassword') !== false, 'updatePassword harus ada.');
        expectTrue(substr_count($source, 'isAJAX()') >= 2, 'update profil dan password perlu cabang AJAX.');
    }

    public function testPasswordPageNoLongerRequired(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/tests/UiUsabilityTest.php');
        expectTrue(strpos($source, 'testPasswordChangeUsesDedicatedPage') === false, 'Assertion lama harus diganti ke modal.');
    }
}
