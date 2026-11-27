<?php

declare(strict_types=1);

final class PublicAccessTest
{
    private const PUBLIC_PATTERNS = [
        '/',
        'mulai-diagnosis',
        'diagnosa*',
        'artikel*',
        'login*',
        'register*',
        'forgot*',
        'reset-password*',
        'activate-account*',
        'resend-activate-account*',
    ];

    public function testLoginFilterExemptsExactlyThePublicRoutes(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Config/Filters.php');
        $matched = preg_match("/'login'\s*=>\s*\[\s*'except'\s*=>\s*\[(.*?)\]\s*,\s*\]\s*,/s", $source, $matches);

        expectSame(1, $matched, 'Entri login harus berupa array dengan except.');
        preg_match_all("/'([^']+)'/", $matches[1], $patterns);
        expectSame(self::PUBLIC_PATTERNS, $patterns[1]);
    }

    public function testGlobalFiltersRemainIntact(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Config/Filters.php');

        expectSame(1, preg_match("/'before'\s*=>\s*\[(.*?)'after'/s", $source, $matches));
        expectTrue(strpos($matches[1], "'honeypot'") !== false, 'Filter honeypot harus tetap aktif.');
        expectTrue(strpos($matches[1], "'csrf'") !== false, 'Filter CSRF harus tetap aktif.');
        expectTrue(strpos($source, 'RoutePolicy::PROTECTED_URI_PATTERNS') !== false, 'Filter admin harus tetap aktif.');
    }

    public function testProtectedRouteGroupsRemainProtected(): void
    {
        $routes = (string) file_get_contents(APP_ROOT . '/app/Config/Routes.php');

        expectSame(1, preg_match("/group\('user',\s*\['filter'\s*=>\s*'login'\]/", $routes));
        expectSame(1, preg_match("/group\('admin',\s*\['filter'\s*=>\s*'admin'\]/", $routes));
        expectSame(1, preg_match("/group\('mining',\s*\['filter'\s*=>\s*'admin'\]/", $routes));
    }

    public function testGuestDiagnosisStillUsesCsrf(): void
    {
        $routes = (string) file_get_contents(APP_ROOT . '/app/Config/Routes.php');
        $view = (string) file_get_contents(APP_ROOT . '/app/Views/diagnosis/index.php');

        expectTrue(strpos($routes, "post('diagnosa/proses'") !== false, 'Route POST diagnosis harus ada.');
        expectTrue(strpos($view, 'csrf_field()') !== false, 'Form diagnosis tamu harus memakai CSRF.');
    }
}
