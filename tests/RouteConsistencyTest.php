<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

final class RouteConsistencyTest
{
    public function testDeclaredRouteUrisAreLowercase(): void
    {
        $source = file_get_contents(APP_ROOT . '/app/Config/Routes.php');
        preg_match_all('/\$routes->(?:get|post)\(\'([^\']+)\'/', $source, $matches);

        foreach ($matches[1] as $uri) {
            if ($uri === '/') {
                continue;
            }

            expectSame(strtolower($uri), $uri, "Route {$uri} must be lowercase");
        }
    }

    public function testApplicationUrlsUseLowercaseSegments(): void
    {
        foreach ($this->phpFiles() as $file) {
            $source = file_get_contents($file);
            preg_match_all('~(?:base_url\([\'"]|[\'"]/)(user|admin)/([A-Za-z0-9_/:-]+)[\'"]~', $source, $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                $path = $match[1] . '/' . $match[2];
                expectSame(strtolower($path), $path, "URL {$path} in {$file} must be lowercase");
            }
        }
    }

    private function phpFiles(): array
    {
        $directory = new RecursiveDirectoryIterator(APP_ROOT . '/app');
        $iterator = new RecursiveIteratorIterator($directory);
        $files = [];

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }
}
