<?php

declare(strict_types=1);

final class AdminRealtimeSearchTest
{
    public function testAllSearchableAdminListsExposeLiveSearchAndListFragments(): void
    {
        $lists = [
            'userlist' => 'userlist',
            'gejala' => 'gejala',
            'penyakit' => 'penyakit',
            'sample' => 'sample',
            'artikel' => 'artikel',
            'decisiontree' => 'decisiontree',
        ];

        foreach ($lists as $view => $fragment) {
            $source = (string) file_get_contents(APP_ROOT . '/app/Views/admin/' . $view . '.php');
            expectTrue(
                strpos($source, 'data-live-search') !== false,
                "Halaman {$view} harus menandai form live search."
            );
            expectTrue(
                strpos($source, 'data-admin-list') !== false,
                "Halaman {$view} harus menyediakan wrapper hasil."
            );
            expectTrue(
                strpos($source, "admin/fragments/{$fragment}") !== false,
                "Halaman {$view} harus memuat fragment {$fragment}."
            );
        }
    }

    public function testAdminListEndpointsReturnFragmentsForAjax(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        $lists = ['userlist', 'gejala', 'penyakit', 'sample', 'artikel', 'decisiontree'];

        foreach ($lists as $list) {
            expectTrue(
                strpos($source, "ajaxResponse(['html' => view('admin/fragments/{$list}', \$data)])") !== false,
                "Endpoint {$list} harus mengembalikan fragment HTML saat AJAX."
            );
        }

        foreach (["view('admin/userlist', \$data)", "view('admin/gejala', \$data)", "view('admin/penyakit', \$data)", "view('admin/sample', \$data)", "view('admin/artikel', \$data)", "view('admin/decisiontree', \$data)"] as $fullView) {
            expectTrue(strpos($source, $fullView) !== false, "Fallback halaman penuh {$fullView} harus dipertahankan.");
        }
    }

    public function testDecisionTreeCurrentPageUsesItsPagerSelector(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');

        expectTrue(
            strpos($source, "\$this->request->getVar('page_decision_tree')") !== false,
            'Nomor halaman decision tree harus membaca selector pager page_decision_tree.'
        );
    }

    public function testEmptySearchResetsTheListPage(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/public/js/admin-crud.js');

        expectTrue(
            strpos($source, 'url.searchParams.delete(form.dataset.pageParam)') !== false,
            'Pencarian baru harus menghapus parameter halaman aktif.'
        );
        expectTrue(
            strpos($source, "url.searchParams.delete('keyword')") !== false,
            'Keyword kosong harus menghapus filter dari URL.'
        );
    }

    public function testOlderSearchResponsesDoNotReplaceNewerResults(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/public/js/admin-crud.js');

        expectTrue(strpos($source, 'list.dataset.requestId') !== false, 'Permintaan list perlu nomor urut.');
        expectTrue(
            strpos($source, 'requestId !== Number(list.dataset.requestId)') !== false,
            'Respons usang tidak boleh mengganti hasil terbaru.'
        );
    }

    public function testPagerRequestsPreserveKeywordAndReplaceOnlyList(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/public/js/admin-crud.js');

        expectTrue(strpos($source, "closest('a.page-link')") !== false, 'Klik pagination harus ditangani AJAX.');
        expectTrue(
            strpos($source, "url.searchParams.set('keyword', keyword.value)") !== false,
            'Pindah halaman harus mempertahankan keyword.'
        );
        expectTrue(
            strpos($source, 'list.innerHTML =') !== false && strpos($source, 'result.payload.html') !== false,
            'Hasil harus mengganti isi list saja.'
        );
    }

    public function testCrudRefreshesListWithoutFullReload(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/public/js/admin-crud.js');

        expectTrue(
            strpos($source, 'requestList(list, currentListUrl(list),') !== false,
            'CRUD sukses harus memuat ulang fragmen list.'
        );
        expectTrue(
            strpos($source, 'if (list)') !== false && strpos($source, 'window.location.reload()') !== false,
            'Hanya form AJAX di luar halaman list yang memakai fallback reload.'
        );
    }

    public function testFailedListRequestsLeaveExistingRowsVisible(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/public/js/admin-crud.js');

        expectTrue(strpos($source, 'setListError(list,') !== false, 'Kegagalan AJAX harus ditampilkan di halaman.');
        expectTrue(
            strpos($source, 'if (!result.ok || !result.payload.status || typeof result.payload.html !== \'string\')') !== false,
            'Respons gagal atau tanpa HTML tidak boleh mengganti hasil yang sedang terlihat.'
        );
    }
}
