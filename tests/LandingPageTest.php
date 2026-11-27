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

        expectTrue(strpos($routes, "get('mulai-diagnosis', 'Home::startDiagnosis')") !== false, 'Route CTA diagnosis harus ada.');
        expectTrue(strpos($controller, 'function startDiagnosis') !== false, 'Home::startDiagnosis harus ada.');
        expectTrue(strpos($controller, "site_url('diagnosa')") !== false, 'CTA diagnosis harus mengarah ke konsultasi tamu.');
    }

    public function testLandingIsStandaloneAndAccessible(): void
    {
        $document = $this->renderLanding();

        expectSame('id', $document->documentElement->getAttribute('lang'));
        expectSame(1, count(TestDom::all($document, '//meta[@name="viewport"]')));
        expectSame(1, count(TestDom::all($document, '//main[@id="main"]')));
        expectSame(0, count(TestDom::all($document, '//style')));
        expectSame(0, count(TestDom::all($document, '//script')));
    }

    public function testLandingLoadsSharedStylesheets(): void
    {
        $document = $this->renderLanding();
        $stylesheets = TestDom::attributes($document, '//link[@rel="stylesheet"]', 'href');

        expectTrue(in_array('http://example.test/css/sb-admin-2.min.css', $stylesheets, true), 'SB Admin harus dimuat.');
        expectTrue(in_array('http://example.test/css/app.css', $stylesheets, true), 'app.css harus dimuat.');
    }

    public function testSkipLinkTargetsMainContent(): void
    {
        $document = $this->renderLanding();
        $skipLink = TestDom::one($document, '//a[contains(@class, "skip-link")]');

        expectSame('#main', $skipLink->getAttribute('href'));
        expectSame('-1', TestDom::one($document, '//main')->getAttribute('tabindex'));
    }

    public function testHeaderLinksToPageSectionsAndLogin(): void
    {
        $document = $this->renderLanding();
        $links = TestDom::attributes($document, '//nav[contains(@class, "dk-public-nav")]//a', 'href');

        expectTrue(in_array('#cara-kerja', $links, true), 'Navigasi harus menuju cara kerja.');
        expectTrue(in_array('#artikel-terbaru', $links, true), 'Navigasi harus menuju artikel.');
        expectTrue(in_array('http://example.test/login', $links, true), 'Navigasi harus menuju login.');
    }

    public function testDiagnosisCtaAndFlowAreExplained(): void
    {
        $document = $this->renderLanding();
        $cta = TestDom::one($document, '//a[contains(@class, "dk-btn-primary")]');
        $text = $document->textContent;

        expectSame('http://example.test/mulai-diagnosis', $cta->getAttribute('href'));
        expectTrue(strpos($cta->textContent, 'Mulai diagnosis') !== false, 'CTA utama harus jelas.');
        expectTrue(strpos($text, 'Gejala') !== false, 'Landing harus menjelaskan tahap gejala.');
        expectTrue(strpos($text, 'C4.5') !== false, 'Landing harus menyebut algoritma C4.5.');
        expectTrue(strpos($text, 'Hasil') !== false, 'Landing harus menjelaskan tahap hasil.');
    }

    public function testRabbitArtworkIsDecorative(): void
    {
        $document = $this->renderLanding();
        $images = TestDom::all($document, '//img[contains(@src, "/img/rabbit.svg")]');

        expectSame(2, count($images));

        foreach ($images as $image) {
            expectSame('', $image->getAttribute('alt'));
            expectSame('true', $image->getAttribute('aria-hidden'));
        }
    }

    public function testStepsAreRenderedAsThreeCards(): void
    {
        $document = $this->renderLanding();
        $cards = TestDom::all($document, '//*[@id="cara-kerja"]//article[contains(@class, "dk-info-card")]');
        $headings = array_map(
            static fn (DOMElement $card): string => TestDom::one($card->ownerDocument, 'h3', $card)->textContent,
            $cards
        );

        expectSame(3, count($cards));
        expectSame(['Pilih gejala', 'Proses aturan', 'Baca hasil'], $headings);
    }

    public function testArticlesUseRenderedDataAndEscapedText(): void
    {
        $renderer = new ViewRenderer();
        $html = $renderer->renderView('app/Views/landing.php', [
            'artikelRingkasan' => [
                [
                    'judul' => '<b>Judul</b> Kelinci',
                    'ringkasan' => 'Ringkasan & artikel',
                    'slug' => 'judul-kelinci',
                ],
            ],
        ]);
        $document = TestDom::document($html);
        $card = TestDom::one($document, '//*[@id="artikel-terbaru"]//article');

        expectTrue(strpos($html, '&lt;b&gt;Judul&lt;/b&gt; Kelinci') !== false, 'Judul artikel harus di-escape.');
        expectSame('<b>Judul</b> Kelinci', TestDom::one($document, './/h3', $card)->textContent);
        expectSame(0, count(TestDom::all($document, './/b', $card)));
        expectSame('http://example.test/artikel/judul-kelinci', TestDom::one($document, './/a[contains(., "Baca artikel")]', $card)->getAttribute('href'));
    }

    public function testSharedStylesCoverPublicComponents(): void
    {
        $styles = (string) file_get_contents(APP_ROOT . '/public/css/app.css');

        foreach (['.skip-link:focus', '.dk-btn-primary', '.dk-btn-outline', '.dk-section-grid a', '.dk-info-card', '.dk-hero-rabbit'] as $selector) {
            expectTrue(strpos($styles, $selector) !== false, "{$selector} harus ada di app.css.");
        }

        expectTrue(strpos($styles, 'color: var(--dk-primary-dark)') !== false, 'Tautan artikel harus memakai primary dark.');
        expectTrue(strpos($styles, '@media (prefers-reduced-motion: reduce)') !== false, 'Preferensi reduced motion harus dihormati.');
        expectTrue(strpos($styles, '@media (max-width: 768px)') !== false, 'Tata letak mobile harus diatur.');
        expectTrue(strpos($styles, '@media (max-width: 1024px)') !== false, 'Tata letak tablet harus diatur.');
    }

    public function testRabbitLogoFollowsTheDashboardReference(): void
    {
        expectTrue(is_file(APP_ROOT . '/public/img/rabbit.svg'), 'SVG kelinci referensi harus tersedia di proyek ini.');

        $sidebar = (string) file_get_contents(APP_ROOT . '/app/Views/templates/sidebar.php');

        expectTrue(strpos($sidebar, 'img/rabbit.svg') !== false, 'Logo dashboard harus memakai SVG kelinci.');
    }

    private function renderLanding(array $articles = []): DOMDocument
    {
        $renderer = new ViewRenderer();

        return TestDom::document($renderer->renderView('app/Views/landing.php', [
            'artikelRingkasan' => $articles,
        ]));
    }
}
