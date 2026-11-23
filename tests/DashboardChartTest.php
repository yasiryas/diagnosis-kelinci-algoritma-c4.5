<?php

declare(strict_types=1);

final class DashboardChartTest
{
    public function testChartQueryAcceptsOptionalUserId(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Models/listModel.php');
        expectTrue(strpos($source, 'function countHasilPerPenyakit(?int $idUser = null): array') !== false, 'Query konsultasi per penyakit harus menerima id user opsional.');
        expectTrue(strpos($source, 'COUNT(*)') !== false, 'Query harus memakai COUNT(*).');
        expectTrue(strpos($source, 'GROUP BY penyakit') !== false, 'Query harus mengelompokkan per penyakit.');
    }

    public function testAdminAndUserPassDifferentScope(): void
    {
        $admin = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        $user = (string) file_get_contents(APP_ROOT . '/app/Controllers/User.php');
        expectTrue(strpos($admin, 'countHasilPerPenyakit()') !== false, 'Admin memakai agregat global.');
        expectTrue(strpos($user, 'countHasilPerPenyakit($id_user)') !== false, 'User memakai agregat milik sendiri.');
    }

    public function testChartDataServiceBuildsPayload(): void
    {
        require_once APP_ROOT . '/app/Services/DashboardChartData.php';
        $data = App\Services\DashboardChartData::payload(
            ['gejala' => 6, 'penyakit' => 4, 'sample' => 37],
            [['penyakit' => 'Coccidiosis', 'total' => 3]]
        );
        expectSame(['Gejala', 'Penyakit', 'Sample'], $data['doughnut']['labels'], 'Label doughnut salah.');
        expectSame([6, 4, 37], $data['doughnut']['values'], 'Nilai doughnut salah.');
        expectSame(['Coccidiosis'], $data['bar']['labels'], 'Label bar salah.');
        expectSame([3], $data['bar']['values'], 'Nilai bar salah.');
        expectSame('#4e73df', $data['doughnut']['colors'][0], 'Warna harus memakai token SB Admin.');
    }

    public function testChartsRenderOnlyOnDashboard(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/user/index.php');
        expectTrue(strpos($source, 'id="dkCompositionChart"') !== false, 'Canvas doughnut harus ada.');
        expectTrue(strpos($source, 'id="dkConsultationChart"') !== false, 'Canvas bar harus ada.');
        expectTrue(strpos($source, 'window.dkChartData') !== false, 'View harus meneruskan payload chart.');
        $layout = (string) file_get_contents(APP_ROOT . '/app/Views/templates/index.php');
        expectTrue(strpos($layout, "renderSection('scripts')") !== false, 'Layout harus membuka section scripts.');
        expectTrue(strpos($layout, 'Chart.min.js') === false, 'Chart.js tidak boleh dimuat global.');
        $js = (string) file_get_contents(APP_ROOT . '/public/js/dashboard-charts.js');
        expectTrue(strpos($js, 'new Chart(') !== false, 'JS harus membuat chart.');
    }
}
