<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

final class FormSecurityTest
{
    public function testPostFormsIncludeCsrfToken(): void
    {
        $directory = new RecursiveDirectoryIterator(APP_ROOT . '/app/Views');
        $iterator = new RecursiveIteratorIterator($directory);

        foreach ($iterator as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $source = file_get_contents($file->getPathname());
            preg_match_all('/<form[^>]*method=[\'"]post[\'"][^>]*>(.{0,400}?)<\/form>/is', $source, $matches);

            foreach ($matches[0] as $form) {
                expectTrue(
                    strpos($form, 'csrf_field') !== false,
                    'POST form in ' . $file->getFilename() . ' must include csrf_field()'
                );
            }
        }
    }
}
