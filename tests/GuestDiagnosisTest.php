<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

final class GuestDiagnosisTest
{
    public function testDiagnosisRoutesArePublic(): void
    {
        $routes = (string) file_get_contents(APP_ROOT . '/app/Config/Routes.php');

        expectTrue(strpos($routes, "get('diagnosa', 'Diagnosis::index')") !== false, 'Route form tamu harus ada.');
        expectTrue(strpos($routes, "post('diagnosa/proses', 'Diagnosis::proses')") !== false, 'Route proses tamu harus POST.');
        expectTrue(strpos($routes, "post('diagnosis', 'User::simpanDiagnosis')") !== false, 'Simpan riwayat harus POST eksplisit.');
        expectTrue(
            strpos($routes, "get('diagnosa'") < strpos($routes, "group('user', ['filter' => 'login']"),
            'Route tamu harus didefinisikan sebelum group login.'
        );
    }

    public function testProsesRejectsEmptySelectionWithoutSaving(): void
    {
        $controller = (string) file_get_contents(APP_ROOT . '/app/Controllers/Diagnosis.php');

        expectTrue(strpos($controller, "Harus memilih gejala minimal 1") !== false, 'Harus ada pesan gejala kosong.');
        expectTrue(strpos($controller, 'saveResult') === false, 'Controller tamu tidak boleh menulis hasil.');
        expectTrue(strpos($controller, 'new DiagnosisRuleMatcher()') !== false, 'Controller tamu harus memakai matcher.');
        expectTrue(strpos($controller, 'penyakitModel') !== false, 'Controller tamu harus memakai penyakitModel.');
    }

    public function testProsesRejectsUnknownDiseaseWithoutSaving(): void
    {
        $controller = (string) file_get_contents(APP_ROOT . '/app/Controllers/Diagnosis.php');

        expectTrue(strpos($controller, 'Penyakit untuk rule ini tidak ditemukan') !== false, 'Harus ada pesan penyakit tidak ditemukan.');
        expectTrue(strpos($controller, 'findAll()') !== false, 'Rule harus diambil dari decisionTreeModel.');
    }

    public function testErrorBranchesKeepSelectedSymptoms(): void
    {
        $controller = (string) file_get_contents(APP_ROOT . '/app/Controllers/Diagnosis.php');

        expectTrue(strpos($controller, "'selected' => \$selected") !== false, 'Error harus mengirim gejala terpilih ke form.');
        expectSame(3, substr_count($controller, '], $selected)'), 'Semua cabang error harus mengirim gejala terpilih.');
    }

    public function testSaveEndpointRequiresExplicitPostAndLogin(): void
    {
        $controller = (string) file_get_contents(APP_ROOT . '/app/Controllers/User.php');

        expectTrue(strpos($controller, 'function simpanDiagnosis') !== false, 'Endpoint simpan harus ada.');
        expectTrue(strpos($controller, 'user() === null') !== false, 'Simpan harus menolak tamu.');
        expectTrue(strpos($controller, "getPost('id_penyakit')") !== false, 'Simpan hanya menerima id penyakit.');
        expectTrue(strpos($controller, 'saveResult(') !== false, 'Simpan harus memanggil saveResult.');
    }
}
