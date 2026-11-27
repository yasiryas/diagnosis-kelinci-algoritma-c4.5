<?php

declare(strict_types=1);

final class UiUsabilityTest
{
    public function testEveryStaticLabelPointsToAnExistingControl(): void
    {
        foreach ($this->viewFiles(APP_ROOT . '/app/Views') as $path) {
            $source = (string) file_get_contents($path);
            preg_match_all('/<label\b[^>]*\bfor="([^"]+)"/', $source, $matches);

            foreach ($matches[1] as $target) {
                if (strpos($target, '<?') !== false) {
                    continue;
                }

                expectTrue(
                    preg_match('/\bid="' . preg_quote($target, '/') . '"/', $source) === 1,
                    str_replace(APP_ROOT . '/', '', $path) . ': label for="' . $target . '" tidak memiliki input yang cocok.'
                );
            }
        }
    }

    public function testCheckboxAndRadioIdsAreUniquePerView(): void
    {
        foreach ($this->viewFiles(APP_ROOT . '/app/Views') as $path) {
            $source = (string) file_get_contents($path);
            preg_match_all('/<input\b[^>]*\btype="(?:checkbox|radio)"[^>]*>/i', $source, $matches);

            $ids = [];
            foreach ($matches[0] as $tag) {
                if (preg_match('/\bid="([^"]+)"/', $tag, $idMatch) === 1) {
                    $ids[] = $idMatch[1];
                }
            }

            expectTrue(
                count($ids) === count(array_unique($ids)),
                str_replace(APP_ROOT . '/', '', $path) . ': id checkbox/radio tidak unik.'
            );
        }
    }

    public function testSearchInputsKeepTheKeyword(): void
    {
        foreach ($this->viewFiles(APP_ROOT . '/app/Views/admin') as $path) {
            $source = (string) file_get_contents($path);

            if (strpos($source, 'name="keyword"') === false) {
                continue;
            }

            expectTrue(
                strpos($source, '$keyword') !== false,
                str_replace(APP_ROOT . '/', '', $path) . ': keyword pencarian harus tetap terisi.'
            );
        }
    }

    public function testSearchPagesShowAnEmptyState(): void
    {
        foreach ($this->viewFiles(APP_ROOT . '/app/Views/admin') as $path) {
            $source = (string) file_get_contents($path);

            if (strpos($source, 'name="keyword"') === false) {
                continue;
            }

            preg_match_all('/admin\/fragments\/([a-z]+)/', $source, $matches);
            foreach ($matches[1] as $fragment) {
                $fragmentPath = APP_ROOT . '/app/Views/admin/fragments/' . $fragment . '.php';
                if (is_file($fragmentPath)) {
                    $source .= (string) file_get_contents($fragmentPath);
                }
            }

            expectTrue(
                strpos($source, 'Tidak ada data') !== false,
                str_replace(APP_ROOT . '/', '', $path) . ': halaman perlu pesan saat hasil kosong.'
            );
        }
    }

    public function testTablesUseResponsiveWrappers(): void
    {
        foreach ($this->viewFiles(APP_ROOT . '/app/Views') as $path) {
            $path = str_replace('\\', '/', $path);

            if (strpos($path, '/errors/') !== false) {
                continue;
            }

            $source = (string) file_get_contents($path);

            if (strpos($source, '<table') === false) {
                continue;
            }

            expectTrue(
                strpos($source, 'table-responsive') !== false,
                str_replace(APP_ROOT . '/', '', $path) . ': tabel harus dibungkus table-responsive.'
            );
        }
    }

    public function testTopbarDividerIsAValidListItem(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/templates/topbar.php');

        expectTrue(strpos($source, '<li class="topbar-divider') !== false, 'Pembatas topbar harus berupa <li>.');
        expectTrue(strpos($source, '<div class="topbar-divider') === false, 'Pembatas topbar bukan <div>.');
    }

    public function testAdminProfileUsesTheProfileView(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');

        expectTrue(strpos($source, "view('admin/profile', \$data)") !== false, 'Admin::profile harus merender admin/profile.');
    }

    public function testAdminProfileUsesTheProfileImageDirectory(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/admin/profile.php');

        expectTrue(strpos($source, "/img/profile/") !== false, 'Foto profil admin harus memakai folder img/profile.');
    }

    public function testSidebarBrandAndProfileLinksAreCorrect(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/templates/sidebar.php');

        expectTrue(
            strpos($source, "base_url(in_groups('admin') ? 'admin' : 'user')") !== false,
            'Brand sidebar harus mengarah ke dashboard sesuai peran.'
        );
        expectTrue(strpos($source, "base_url('admin/profile')") !== false, 'Admin harus memiliki tautan profil admin.');
        expectTrue(strpos($source, "base_url('user/profile')") !== false, 'User tetap memiliki tautan profil user.');
    }

    public function testDiagnosisCheckboxLabelUsesTheSameId(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/user/prosesdiagnosis.php');

        expectTrue(
            strpos($source, 'id="gejala-<?= $g[\'id_gejala\']; ?>"') !== false,
            'Checkbox diagnosis harus memakai id unik per gejala.'
        );
        expectTrue(
            strpos($source, 'for="gejala-<?= $g[\'id_gejala\']; ?>"') !== false,
            'Label diagnosis harus mengarah ke id checkbox yang sama.'
        );
    }

    public function testPasswordChangeUsesModal(): void
    {
        $edit = (string) file_get_contents(APP_ROOT . '/app/Views/user/edit.php');
        $modal = (string) file_get_contents(APP_ROOT . '/app/Views/templates/modal.php');

        expectTrue(strpos($edit, 'data-target="#passwordModal"') !== false, 'Tombol ganti password harus membuka modal.');
        expectTrue(strpos($modal, 'id="passwordModal"') !== false, 'Modal password harus tersedia.');
        expectTrue(strpos($modal, 'changePasswordModal') === false, 'Modal password lama harus dihapus.');
    }

    public function testRoleIsShownAsABadge(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/admin/fragments/userlist.php');

        expectTrue(strpos($source, 'class="badge') !== false, 'Role harus ditampilkan sebagai badge.');
        expectTrue(strpos($source, '<button type="text"') === false, 'Role tidak boleh memakai button type="text".');
    }

    public function testSamplePageLinksToMiningResult(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/admin/sample.php');

        expectTrue(strpos($source, "base_url('mining/hitung')") !== false, 'Halaman sample harus memiliki tautan hasil mining.');
    }

    private function viewFiles(string $directory): array
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        sort($files);

        return $files;
    }
}
