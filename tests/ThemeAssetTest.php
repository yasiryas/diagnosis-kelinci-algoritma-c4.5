<?php

declare(strict_types=1);

final class ThemeAssetTest
{
    public function testLayoutUsesOneJquery(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/templates/index.php');
        expectTrue(strpos($source, 'jquery-3.2.1.slim.min.js') === false, 'jQuery slim duplikat harus dihapus.');
        expectTrue(substr_count($source, 'jquery.min.js') === 1, 'Hanya satu jQuery full yang boleh dimuat.');
    }

    public function testLayoutAndLandingShareNunitoWeights(): void
    {
        foreach (['app/Views/templates/index.php', 'app/Views/landing.php'] as $file) {
            $source = (string) file_get_contents(APP_ROOT . '/' . $file);
            expectTrue(strpos($source, 'Nunito:200,300,400,600,700,800,900') !== false, "{$file} harus memakai weight Nunito yang sama.");
        }
    }

    public function testAppCssDefinesThemeTokens(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/public/css/app.css');
        foreach (['--dk-primary', '--dk-primary-dark', '--dk-light', '--dk-border'] as $token) {
            expectTrue(strpos($source, $token) !== false, "{$token} harus ada.");
        }
    }

    public function testAppCssIsLoaded(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/templates/index.php');
        expectTrue(strpos($source, 'css/app.css') !== false, 'Layout harus memuat app.css.');
    }
}
