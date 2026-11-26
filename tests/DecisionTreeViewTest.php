<?php

declare(strict_types=1);

final class DecisionTreeViewTest
{
    public function testAdminControllerUsesDecisionTreeGraph(): void
    {
        $controller = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');

        expectTrue(strpos($controller, 'new DecisionTreeGraph(') !== false, 'Admin harus memakai DecisionTreeGraph.');
        expectTrue(strpos($controller, "'svg' =>") !== false, 'Admin harus mengirim svg ke view.');
        expectTrue(strpos($controller, "'treeList' =>") !== false, 'Admin harus mengirim daftar aksesibel ke view.');
        expectTrue(strpos($controller, 'function buildDecisionTreeHtml') === false, 'Builder HTML lama harus dihapus.');
        expectTrue(strpos($controller, 'function renderTreeNode') === false, 'Renderer list lama harus dihapus.');
    }

    public function testDecisionTreeViewUsesSvgAndCollapsesRawRules(): void
    {
        $view = (string) file_get_contents(APP_ROOT . '/app/Views/admin/decisiontree.php');
        $fragment = (string) file_get_contents(APP_ROOT . '/app/Views/admin/fragments/decisiontree.php');
        $css = (string) file_get_contents(APP_ROOT . '/public/css/app.css');

        expectTrue(strpos($view, '<?= $svg; ?>') !== false, 'View harus merender svg.');
        expectTrue(strpos($view, '<?= $treeList; ?>') !== false, 'View harus merender fallback aksesibel.');
        expectTrue(strpos($fragment, '<details') !== false, 'Tabel aturan harus berada di dalam details.');
        expectTrue(strpos($fragment, 'Lihat aturan mentah') !== false, 'Ringkasan details harus berbahasa Indonesia.');
        expectTrue(strpos($fragment, '<table') !== false, 'Tabel tetap dipertahankan sebagai fallback.');
        expectTrue(strpos($css, '.decision-tree-svg') !== false, 'CSS svg harus tersedia.');
    }
}
