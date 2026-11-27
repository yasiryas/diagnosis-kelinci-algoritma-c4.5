<?php

declare(strict_types=1);

final class LoginPageTest
{
    public function testLoginUsesDecorativeRabbitArtwork(): void
    {
        $document = $this->renderLogin();
        $rabbit = TestDom::one($document, '//img[contains(@src, "/img/rabbit.svg")]');

        expectSame('http://example.test/img/rabbit.svg', $rabbit->getAttribute('src'));
        expectSame('', $rabbit->getAttribute('alt'));
        expectSame('true', $rabbit->getAttribute('aria-hidden'));
        // Rabbit is inside the card header
        expectSame(1, count(TestDom::all($document, '//div[contains(@class, "card")]//img[contains(@src, "/img/rabbit.svg")]')));
    }

    public function testHomeLinkStaysVisibleOutsideHiddenVisualPanel(): void
    {
        $document = $this->renderLogin();
        $link = TestDom::one($document, '//a[contains(@class, "small") and contains(., "Kembali ke beranda")]');

        expectSame('http://example.test/', $link->getAttribute('href'));
        expectTrue(strpos($link->textContent, 'Kembali ke beranda') !== false, 'Tautan beranda harus ada di panel.');
    }

    public function testLoginFieldsHaveProgrammaticLabelsAndInlineErrors(): void
    {
        $GLOBALS['dkSession'] = [
            'errors.login' => 'Email tidak ditemukan.',
            'errors.password' => 'Kata sandi salah.',
        ];

        try {
            $document = $this->renderLogin();

            foreach (['login', 'password'] as $field) {
                $label = TestDom::one($document, "//label[@for=\"{$field}\"]");
                $input = TestDom::one($document, "//input[@id=\"{$field}\"]");
                $feedback = TestDom::one($document, "//div[@id=\"{$field}-error\"]");

                expectTrue(strpos($input->getAttribute('class'), 'is-invalid') !== false, "Input {$field} harus ditandai invalid.");
                expectSame('true', $input->getAttribute('aria-invalid'));
                expectSame("{$field}-error", $input->getAttribute('aria-describedby'));
                expectTrue(strpos($feedback->textContent, 'ditemukan') !== false || strpos($feedback->textContent, 'salah') !== false, 'Error harus dekat field.');
                expectTrue(strpos($label->textContent, 'email') !== false || strpos($label->textContent, 'sandi') !== false, 'Label harus terlihat.');
            }
        } finally {
            unset($GLOBALS['dkSession']);
        }
    }

    public function testLoginAdaptsFieldsToAuthConfiguration(): void
    {
        $renderer = new ViewRenderer();
        $document = TestDom::document($renderer->renderPage('app/Views/auth/login.php', [
            'config' => ViewRenderer::authConfig(['validFields' => ['username']]),
        ]));

        expectSame('text', TestDom::one($document, '//input[@id="login"]')->getAttribute('type'));
        expectSame('Email atau nama pengguna', TestDom::one($document, '//label[@for="login"]')->textContent);

        $document = TestDom::document($renderer->renderPage('app/Views/auth/login.php', [
            'config' => ViewRenderer::authConfig([
                'allowRemembering' => false,
                'allowRegistration' => false,
                'activeResetter' => false,
            ]),
        ]));

        expectSame(0, count(TestDom::all($document, '//input[@id="remember"]')));
        expectSame(0, count(TestDom::all($document, '//a[contains(@href, "register")]')));
        expectSame(0, count(TestDom::all($document, '//a[contains(@href, "forgot")]')));
    }

    public function testLoginPreservesMythAuthFormContract(): void
    {
        $document = $this->renderLogin();
        $form = TestDom::one($document, '//form');

        expectSame('http://example.test/login', $form->getAttribute('action'));
        expectSame('post', $form->getAttribute('method'));
        expectSame(1, count(TestDom::all($document, '//input[@name="csrf_test_name"]')));
        expectSame('http://example.test/register', TestDom::one($document, '//a[contains(@href, "register")]')->getAttribute('href'));
        expectSame('http://example.test/forgot', TestDom::one($document, '//a[contains(@href, "forgot")]')->getAttribute('href'));
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
