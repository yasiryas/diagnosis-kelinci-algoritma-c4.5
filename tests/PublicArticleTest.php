<?php

declare(strict_types=1);

final class PublicArticleTest
{
    public function testArticleRoutesUseSlugPattern(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Config/Routes.php');
        expectTrue(strpos($source, "get('artikel', 'Artikel::index')") !== false, 'Rute daftar artikel harus ada.');
        expectTrue(strpos($source, "addPlaceholder('slug', '[a-z0-9-]+')") !== false, 'CI4 4.1.1 butuh placeholder slug kustom.');
        expectTrue(strpos($source, "get('artikel/(:slug)', 'Artikel::show/\$1')") !== false, 'Rute detail harus memakai placeholder slug kustom.');
        expectTrue(strpos($source, "get('artikel/{slug}'") === false, 'Placeholder {slug} tidak didukung CI4 4.1.1.');
    }

    public function testPublicArticleViewsUseStandaloneLayout(): void
    {
        foreach (['app/Views/artikel/index.php', 'app/Views/artikel/show.php'] as $file) {
            $source = (string) file_get_contents(APP_ROOT . '/' . $file);
            expectTrue(strpos($source, "\$this->extend('templates/public')") !== false, "{$file} harus memakai layout publik.");
        }
        foreach (['app/Views/artikel/index.php', 'app/Views/landing.php'] as $file) {
            $source = (string) file_get_contents(APP_ROOT . '/' . $file);
            expectTrue(strpos($source, "esc(\$item['slug'], 'attr')") !== false, "{$file} harus meng-escape slug pada atribut href.");
        }
        $layout = (string) file_get_contents(APP_ROOT . '/app/Views/templates/public.php');
        expectTrue(strpos($layout, 'css/app.css') !== false, 'Layout publik harus memuat app.css.');
        expectTrue(strpos($layout, 'templates/sidebar') === false, 'Layout publik tidak boleh memuat sidebar admin.');
    }

    public function testLandingShowsArticleSummary(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/landing.php');
        expectTrue(strpos($source, '$artikelRingkasan') !== false, 'Landing harus merender ringkasan artikel.');
        expectTrue(strpos($source, "base_url('artikel/") !== false, 'Ringkasan harus menaut ke detail artikel.');
        $controller = (string) file_get_contents(APP_ROOT . '/app/Controllers/Home.php');
        expectTrue(strpos($controller, 'getPublished(3)') !== false, 'Home harus memuat tiga artikel terbaru.');
    }

    public function testArticleDetailUsesPublishedQueryAndReturns404(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Artikel.php');
        expectTrue(strpos($source, 'getPublishedBySlug') !== false, 'Detail harus memakai query publik.');
        expectTrue(strpos($source, 'setStatusCode(404)') !== false, 'Artikel tidak ditemukan harus 404.');
    }
}
