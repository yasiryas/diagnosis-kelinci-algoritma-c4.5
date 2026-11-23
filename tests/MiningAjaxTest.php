<?php

declare(strict_types=1);

final class MiningAjaxTest
{
    public function testMiningRoutesUsePostForMutations(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Config/Routes.php');
        expectTrue(strpos($source, "post('mining', 'Mining::mining')") !== false, 'mining harus POST.');
        expectTrue(strpos($source, "post('kosong', 'Mining::kosong')") !== false, 'kosong harus POST.');
        expectTrue(strpos($source, "get('hitung', 'Mining::hitung')") !== false, 'hitung tetap GET.');
    }

    public function testMiningMutationViewsPostCsrf(): void
    {
        foreach (['sample.php', 'lapormining.php'] as $file) {
            $source = (string) file_get_contents(APP_ROOT . '/app/Views/admin/' . $file);
            expectTrue(strpos($source, 'method="post"') !== false, "{$file} harus POST.");
            expectTrue(strpos($source, 'csrf_field()') !== false, "{$file} harus CSRF.");
        }
    }

    public function testMiningControllerHasAjaxBranch(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Mining.php');
        expectTrue(substr_count($source, 'isAJAX()') >= 2, 'mining dan kosong perlu cabang AJAX.');
    }
}
