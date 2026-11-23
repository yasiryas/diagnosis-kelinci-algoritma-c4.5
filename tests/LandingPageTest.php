<?php

declare(strict_types=1);

final class LandingPageTest
{
    public function testRootRouteUsesLandingPage(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Config/Routes.php');

        expectTrue(strpos($source, "get('/', 'Home::landing')") !== false, 'Route / harus merender Home::landing.');
    }

    public function testHomeControllerSetsDashboardAsDefaultLoginDestination(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Home.php');

        expectTrue(strpos($source, 'redirect_url') !== false, 'Home::landing harus menyiapkan redirect_url.');
        expectTrue(strpos($source, "site_url('user')") !== false, 'redirect_url bawaan harus mengarah ke dashboard.');
    }

    public function testDiagnosisCtaStartsGuestConsultation(): void
    {
        $routes = (string) file_get_contents(APP_ROOT . '/app/Config/Routes.php');
        $controller = (string) file_get_contents(APP_ROOT . '/app/Controllers/Home.php');
        $view = (string) file_get_contents(APP_ROOT . '/app/Views/landing.php');

        expectTrue(strpos($routes, "get('mulai-diagnosis', 'Home::startDiagnosis')") !== false, 'Route CTA diagnosis harus ada.');
        expectTrue(strpos($controller, 'function startDiagnosis') !== false, 'Home::startDiagnosis harus ada.');
        expectTrue(strpos($controller, "site_url('diagnosa')") !== false, 'CTA diagnosis harus mengarah ke konsultasi tamu.');
        expectTrue(strpos($view, "base_url('mulai-diagnosis')") !== false, 'CTA landing harus memakai route diagnosis.');
    }

    public function testLandingViewIsStandaloneAndAccessible(): void
    {
        $path = APP_ROOT . '/app/Views/landing.php';

        expectTrue(is_file($path), 'View landing.php harus ada.');

        $source = (string) file_get_contents($path);

        expectTrue(strpos($source, '<!DOCTYPE html>') !== false, 'Landing harus memiliki doctype.');
        expectTrue(strpos($source, 'lang="id"') !== false, 'Landing harus menggunakan lang="id".');
        expectTrue(strpos($source, 'name="viewport"') !== false, 'Landing harus responsif.');
        expectTrue(strpos($source, "base_url('login')") !== false, 'Landing harus menautkan ke form login.');
        expectTrue(strpos($source, 'Mulai diagnosis') !== false, 'Landing harus memiliki CTA utama.');
    }

    public function testLandingExplainsDiagnosisFlow(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/landing.php');

        expectTrue(strpos($source, 'Gejala') !== false, 'Landing harus menjelaskan tahap gejala.');
        expectTrue(strpos($source, 'C4.5') !== false, 'Landing harus menyebut algoritma C4.5.');
        expectTrue(strpos($source, 'Hasil') !== false, 'Landing harus menjelaskan tahap hasil.');
    }

    public function testLandingUsesSbAdminThemeTokens(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/landing.php');

        expectTrue(strpos($source, 'css/sb-admin-2.min.css') !== false, 'Landing harus memuat CSS SB Admin.');
        expectTrue(strpos($source, '#4e73df') !== false, 'Landing harus memakai primary SB Admin.');
        expectTrue(strpos($source, '#224abe') !== false, 'Landing harus memakai primary dark SB Admin.');
        expectTrue(strpos($source, '#f8f9fc') !== false, 'Landing harus memakai background light SB Admin.');
        expectTrue(strpos($source, '#e3e6f0') !== false, 'Landing harus memakai border kartu SB Admin.');
    }

    public function testRabbitLogoFollowsTheDashboardReference(): void
    {
        expectTrue(is_file(APP_ROOT . '/public/img/rabbit.svg'), 'SVG kelinci referensi harus tersedia di proyek ini.');

        $landing = (string) file_get_contents(APP_ROOT . '/app/Views/landing.php');
        $sidebar = (string) file_get_contents(APP_ROOT . '/app/Views/templates/sidebar.php');

        expectTrue(strpos($landing, "base_url('img/rabbit.svg')") !== false, 'Logo landing harus memakai SVG kelinci.');
        expectTrue(strpos($sidebar, 'img/rabbit.svg') !== false, 'Logo dashboard harus memakai SVG kelinci.');
    }
}
