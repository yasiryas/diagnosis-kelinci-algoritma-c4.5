<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

final class GuestDiagnosisViewTest
{
    public function testGuestFormUsesCsrfAndUniqueCheckboxIds(): void
    {
        $view = (string) file_get_contents(APP_ROOT . '/app/Views/diagnosis/index.php');

        expectTrue(strpos($view, "extend('templates/public')") !== false, 'Form tamu harus memakai layout publik.');
        expectTrue(strpos($view, 'csrf_field()') !== false, 'Form tamu harus punya CSRF.');
        expectTrue(strpos($view, 'id="gejala-<?= $g[\'id_gejala\']; ?>"') !== false, 'Checkbox harus punya id unik.');
        expectTrue(strpos($view, 'for="gejala-<?= $g[\'id_gejala\']; ?>"') !== false, 'Label harus mengarah ke checkbox yang sama.');
        expectTrue(strpos($view, 'id="diagnosis-errors"') !== false, 'Harus ada error summary.');
        expectTrue(strpos($view, "base_url('diagnosa/proses')") !== false, 'Form harus POST ke endpoint tamu.');
        expectTrue(strpos($view, '$selected') !== false, 'Checkbox harus memakai daftar gejala terpilih.');
        expectTrue(strpos($view, "'checked'") !== false, 'Checkbox yang cocok harus ditandai checked.');
    }

    public function testResultPageSeparatesGuestAndMemberActions(): void
    {
        $view = (string) file_get_contents(APP_ROOT . '/app/Views/diagnosis/hasil.php');

        expectTrue(strpos($view, 'user() === null') !== false, 'CTA login harus khusus tamu.');
        expectTrue(strpos($view, "base_url('login')") !== false, 'Tamu harus diarahkan ke login.');
        expectTrue(strpos($view, "base_url('user/diagnosis')") !== false, 'Simpan riwayat harus POST ke user/diagnosis.');
        expectTrue(strpos($view, 'csrf_field()') !== false, 'Form simpan harus punya CSRF.');
        expectTrue(strpos($view, 'Simpan riwayat') !== false, 'Tombol simpan harus eksplisit.');
        expectTrue(strpos($view, 'Masuk ke akun') !== false, 'CTA tamu tidak boleh menjanjikan penyimpanan riwayat.');
        expectTrue(strpos($view, 'Masuk untuk menyimpan riwayat') === false, 'CTA tamu tidak boleh menjanjikan penyimpanan riwayat.');
        expectTrue(strpos($view, 'saveResult') === false, 'View tidak boleh menyimpan langsung.');
    }

    public function testPublicCtaPointsToGuestDiagnosis(): void
    {
        foreach (['app/Views/landing.php', 'app/Views/templates/public.php'] as $path) {
            $view = (string) file_get_contents(APP_ROOT . '/' . $path);
            expectTrue(strpos($view, "base_url('mulai-diagnosis')") !== false, $path . ': CTA harus tetap memakai route mulai-diagnosis.');
        }

        $home = (string) file_get_contents(APP_ROOT . '/app/Controllers/Home.php');
        expectTrue(strpos($home, "site_url('diagnosa')") !== false, 'CTA harus mengarah ke /diagnosa.');
    }
}
