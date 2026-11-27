<?php

declare(strict_types=1);

final class AuthLayoutTest
{
    public function testAuthLayoutUsesIndonesianLanguage(): void
    {
        $document = $this->renderLogin();

        expectSame('id', $document->documentElement->getAttribute('lang'));
    }

    public function testEveryAuthPageSetsItsOwnTitle(): void
    {
        $titles = [
            'login' => 'Masuk — Diagnosis Kelinci',
            'register' => 'Daftar — Diagnosis Kelinci',
            'forgot' => 'Lupa kata sandi — Diagnosis Kelinci',
            'reset' => 'Atur ulang kata sandi — Diagnosis Kelinci',
            'change' => 'Ganti kata sandi — Diagnosis Kelinci',
        ];

        $renderer = new ViewRenderer();

        foreach ($titles as $page => $title) {
            $document = TestDom::document($renderer->renderPage(
                "app/Views/auth/{$page}.php",
                ['config' => ViewRenderer::authConfig()]
            ));

            expectSame($title, TestDom::one($document, '//title')->textContent);
        }
    }

    public function testAuthLayoutFallsBackToBrandTitle(): void
    {
        $renderer = new ViewRenderer();
        $document = TestDom::document($renderer->renderLayout(
            'app/Views/auth/templates/index.php',
            '<p>Konten</p>'
        ));

        expectSame('Diagnosis Kelinci', TestDom::one($document, '//title')->textContent);
    }

    public function testAuthLayoutLoadsSharedStylesheets(): void
    {
        $document = $this->renderLogin();
        $stylesheets = TestDom::attributes($document, '//link[@rel="stylesheet"]', 'href');

        expectTrue(in_array('http://example.test/css/sb-admin-2.min.css', $stylesheets, true), 'SB Admin harus dimuat.');
        expectTrue(in_array('http://example.test/css/app.css', $stylesheets, true), 'app.css harus dimuat.');
    }

    private function renderLogin(): DOMDocument
    {
        $renderer = new ViewRenderer();

        return TestDom::document($renderer->renderPage(
            'app/Views/auth/login.php',
            ['config' => ViewRenderer::authConfig()]
        ));
    }
}
