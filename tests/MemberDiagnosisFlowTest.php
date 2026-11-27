<?php

declare(strict_types=1);

final class MemberDiagnosisFlowTest
{
    public function testMemberDiagnosisEntrypointsRedirectToSharedFlow(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/User.php');

        foreach (['diagnosis', 'prosesDiagnosis'] as $method) {
            $start = strpos($source, 'public function ' . $method . '()');
            expectTrue($start !== false, "Method {$method} harus tetap tersedia.");

            $nextMethod = strpos($source, 'public function ', $start + 1);
            $body = substr($source, $start, $nextMethod === false ? null : $nextMethod - $start);
            expectTrue(
                strpos($body, "redirect()->to(site_url('diagnosa'))") !== false,
                "Method {$method} harus memakai alur diagnosis bersama."
            );
        }
    }

    public function testMemberKonsultasiRouteUsesSharedNonSavingProcessor(): void
    {
        $routes = (string) file_get_contents(APP_ROOT . '/app/Config/Routes.php');
        $diagnosis = (string) file_get_contents(APP_ROOT . '/app/Controllers/Diagnosis.php');

        expectTrue(
            strpos($routes, "post('konsultasi', 'Diagnosis::proses')") !== false,
            'POST user/konsultasi harus memakai processor diagnosis bersama.'
        );
        expectTrue(strpos($diagnosis, 'saveResult') === false, 'Processor bersama tidak boleh menyimpan riwayat otomatis.');
    }

    public function testMemberHistorySaveRemainsAnExplicitPost(): void
    {
        $routes = (string) file_get_contents(APP_ROOT . '/app/Config/Routes.php');
        $user = (string) file_get_contents(APP_ROOT . '/app/Controllers/User.php');

        expectTrue(
            strpos($routes, "post('diagnosis', 'User::simpanDiagnosis')") !== false,
            'Penyimpanan riwayat harus tetap memakai POST yang dilindungi login.'
        );
        expectTrue(strpos($user, 'public function simpanDiagnosis()') !== false, 'Handler simpan riwayat harus tersedia.');
        expectTrue(strpos($user, '$this->decisionTreeModel->saveResult(') !== false, 'Riwayat hanya disimpan oleh handler eksplisit.');
    }
}
