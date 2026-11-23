<?php

declare(strict_types=1);

final class AjaxModalTest
{
    public function testCrudFormsAreMarkedForAjax(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/templates/modal.php');
        expectTrue(strpos($source, 'data-ajax-form') !== false, 'Form CRUD perlu marker AJAX.');
        expectTrue(strpos($source, 'data-error-summary') !== false, 'Form CRUD perlu error summary.');
    }

    public function testModalIdsAreUnique(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/templates/modal.php');
        preg_match_all('/id="([^"]+)"/', $source, $matches);
        expectTrue(count($matches[1]) === count(array_unique($matches[1])), 'Id modal tidak boleh dobel.');
    }

    public function testAjaxScriptIsLoadedAfterSbAdmin(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/templates/index.php');
        expectTrue(strpos($source, 'js/admin-crud.js') !== false, 'Layout harus memuat admin-crud.js.');
        expectTrue(strpos($source, 'csrf-token') !== false, 'Layout harus memuat meta csrf-token.');
    }

    public function testAdminCrudJsRefreshesCsrf(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/public/js/admin-crud.js');
        expectTrue(strpos($source, "payload.csrf") !== false, 'JS harus memakai csrf dari respons.');
        expectTrue(strpos($source, 'FormData') !== false, 'JS harus mengirim FormData.');
    }

    public function testAdminCrudJsShowsInlineErrors(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/public/js/admin-crud.js');
        expectTrue(strpos($source, 'invalid-feedback') !== false, 'JS harus menampilkan invalid-feedback.');
        expectTrue(strpos($source, 'focus()') !== false, 'JS harus memindahkan fokus ke error summary.');
    }
}
