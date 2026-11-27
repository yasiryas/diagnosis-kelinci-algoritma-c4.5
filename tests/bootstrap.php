<?php

declare(strict_types=1);

define('APP_ROOT', dirname(__DIR__));

require_once __DIR__ . '/Support/ViewRenderer.php';

spl_autoload_register(static function (string $class): void {
    $prefixes = [
        'App\\' => APP_ROOT . '/app/',
        'Config\\' => APP_ROOT . '/app/Config/',
    ];

    foreach ($prefixes as $prefix => $directory) {
        if (strpos($class, $prefix) !== 0) {
            continue;
        }

        $path = $directory . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';

        if (is_file($path)) {
            require_once $path;
        }
    }
});

function expectSame($expected, $actual, string $message = 'Nilai tidak sesuai.'): void
{
    if ($expected !== $actual) {
        throw new RuntimeException($message . ' — expected ' . var_export($expected, true) . ', got ' . var_export($actual, true));
    }
}

function expectTrue(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function expectNull($actual, string $message): void
{
    if ($actual !== null) {
        throw new RuntimeException($message . ' — expected null, got ' . var_export($actual, true));
    }
}
