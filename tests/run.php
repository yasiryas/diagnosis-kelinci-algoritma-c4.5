<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$files = glob(__DIR__ . '/*Test.php');
sort($files);
$failures = 0;
$total = 0;

foreach ($files as $file) {
    $class = basename($file, '.php');
    require_once $file;

    $test = new $class();

    foreach (get_class_methods($test) as $method) {
        if (strpos($method, 'test') !== 0) {
            continue;
        }

        $total++;

        try {
            $test->{$method}();
            echo "PASS {$class}::{$method}\n";
        } catch (Throwable $error) {
            $failures++;
            echo "FAIL {$class}::{$method}\n";
            echo '  ' . $error->getMessage() . "\n";
        }
    }
}

echo "\n{$total} tests, {$failures} failures\n";

exit($failures === 0 ? 0 : 1);
