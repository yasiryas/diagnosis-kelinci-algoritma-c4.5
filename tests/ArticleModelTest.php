<?php

declare(strict_types=1);

final class ArticleModelTest
{
    public function testArticleModelDefinesRequiredQueries(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Models/artikelModel.php');
        foreach (['getPublished', 'getPublishedBySlug', 'getAdminArticles', 'getCategories', 'getCategoryOptions', 'getCategoryIds', 'findById', 'findBySlug', 'slugExists', 'saveCategories'] as $method) {
            expectTrue(strpos($source, 'function ' . $method) !== false, "artikelModel butuh {$method}.");
        }
    }

    public function testPublishedQueriesForcePublishStatus(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Models/artikelModel.php');
        expectTrue(strpos($source, "'publish'") !== false, 'Query publik harus memfilter status publish.');
        expectTrue(strpos($source, 'artikel_kategori') !== false, 'Query artikel harus menggabungkan kategori lewat pivot.');
    }

    public function testSaveCategoriesUsesArticlePivot(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Models/artikelModel.php');
        expectTrue(strpos($source, "'artikel_kategori'") !== false, 'Pivot artikel_kategori harus dipakai.');
        expectTrue(strpos($source, '(int) $articleId') !== false, 'Id artikel harus di-cast.');
    }
}
