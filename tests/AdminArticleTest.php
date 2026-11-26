<?php

declare(strict_types=1);

final class AdminArticleTest
{
    public function testArticleCrudMethodsBranchOnAjax(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        foreach (['artikel', 'tambahArtikel', 'updateArtikel', 'deleteArtikel'] as $method) {
            expectTrue(strpos($source, 'function ' . $method) !== false, "Admin butuh {$method}.");
        }
        expectTrue(substr_count($source, 'isAJAX()') >= 12, 'CRUD artikel perlu cabang isAJAX().');
    }

    public function testArticleCrudReturnsFieldErrorsForDuplicateSlug(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        expectTrue(strpos($source, "'errors' => ['slug'") !== false, 'Slug duplikat harus mengembalikan errors.slug.');
        expectTrue(strpos($source, "'slugExists'") !== false || strpos($source, 'slugExists(') !== false, 'Controller harus memakai slugExists.');
        expectTrue(strpos($source, 'alpha_dash') !== false, 'Slug harus divalidasi alpha_dash.');
    }

    public function testArticleListSuppliesCategoryIdForEditing(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        expectTrue(strpos($source, "\$article['kategori_id'] = \$firstCategory;") !== false, 'Daftar artikel harus mengirim kategori_id ke view.');
    }

    public function testArticleSearchUsesIndonesianLabel(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/admin/artikel.php');
        expectTrue(strpos($source, '>Cari</button>') !== false, 'Tombol pencarian harus berbahasa Indonesia.');
    }

    public function testArticleViewExposesRowMarkers(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/admin/fragments/artikel.php');
        expectTrue(strpos($source, 'data-row-id=') !== false, 'Baris artikel butuh data-row-id.');
        foreach (['judul', 'slug', 'status', 'kategori'] as $field) {
            expectTrue(strpos($source, 'data-field="' . $field . '"') !== false, "Baris artikel butuh data-field {$field}.");
        }
    }

    public function testArticleModalsUseAjaxMarkers(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/templates/modal.php');
        foreach (['artikelModal', 'editArtikelModal', 'deleteArtikelModal'] as $id) {
            expectTrue(strpos($source, 'id="' . $id . '"') !== false, "Modal {$id} harus ada.");
        }
        expectTrue(substr_count($source, 'data-ajax-form') >= 12, 'Semua form artikel harus bermarker AJAX.');
    }

    public function testArticleNonAjaxFallbackStillRedirects(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        expectTrue(substr_count($source, "redirect()->to('admin/artikel')") >= 3, 'Fallback non-AJAX artikel tetap redirect.');
    }

    public function testArticleSlugService(): void
    {
        require_once APP_ROOT . '/app/Services/ArticleSlug.php';
        $service = new App\Services\ArticleSlug();
        expectSame('cara-merawat-kelinci-sembelit', $service->fromJudul('Cara Merawat Kelinci Sembelit'), 'Slug judul salah.');
        expectSame('nutrisi-untuk-kelinci-2', $service->unique('nutrisi-untuk-kelinci', static function (string $slug): bool {
            return $slug === 'nutrisi-untuk-kelinci';
        }), 'Slug unik salah.');
        expectSame('judul-baru', $service->fromJudul('Judul, Baru!'), 'Karakter bukan kata harus dibuang.');
    }
}
