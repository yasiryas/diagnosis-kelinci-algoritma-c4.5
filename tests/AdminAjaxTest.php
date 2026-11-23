<?php

declare(strict_types=1);

final class AdminAjaxTest
{
    public function testBaseControllerProvidesJsonResponseHelper(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/BaseController.php');
        expectTrue(strpos($source, 'function ajaxResponse(') !== false, 'BaseController butuh helper ajaxResponse.');
        expectTrue(strpos($source, "'csrf'") !== false, 'Respons AJAX harus mengembalikan csrf baru.');
    }

    public function testAdminCrudMethodsBranchOnAjax(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        foreach (['tambahGejala', 'updateGejala', 'deleteGejala', 'tambahPenyakit', 'updatePenyakit', 'deletePenyakit', 'tambahSample', 'updateSample', 'deleteSample'] as $method) {
            expectTrue(strpos($source, 'function ' . $method) !== false, "Method {$method} harus ada.");
        }
        expectTrue(substr_count($source, 'isAJAX()') >= 9, 'Semua CRUD admin perlu cabang isAJAX().');
    }

    public function testAdminAjaxValidationReturnsFieldErrors(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        expectTrue(strpos($source, "'errors' =>") !== false, 'Respons validasi AJAX harus memuat errors per field.');
        expectTrue(strpos($source, '422') !== false, 'Validasi AJAX harus memakai status 422.');
    }

    public function testAjaxResponseRefreshesCsrfToken(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/BaseController.php');
        expectTrue(strpos($source, 'csrf_hash()') !== false, 'Helper AJAX harus membaca csrf_hash().');
    }

    public function testAdminNonAjaxFallbackStillRedirects(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        expectTrue(strpos($source, "redirect()->to('admin/gejala')") !== false, 'Fallback non-AJAX gejala tetap redirect.');
        expectTrue(strpos($source, "redirect()->to('admin/datasample')") !== false, 'Fallback non-AJAX sample tetap redirect.');
    }
}
