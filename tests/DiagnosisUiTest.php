<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

final class DiagnosisUiTest
{
    public function testDiagnosisFormRendersGroupedSelectableSymptoms(): void
    {
        $document = $this->renderForm();
        $form = TestDom::one($document, '//form[@action="http://example.test/diagnosa/proses"]');

        expectSame('post', $form->getAttribute('method'));
        expectSame(1, count(TestDom::all($document, '//input[@name="csrf_test_name"]')));
        expectSame(2, count(TestDom::all($document, '//fieldset[contains(concat(" ", normalize-space(@class), " "), " diagnosis-symptom-group ")]')));
        expectSame(3, count(TestDom::all($document, '//input[@type="checkbox" and @name="gejala[]"]')));
        expectSame(
            3,
            count(TestDom::all($document, '//label[contains(concat(" ", normalize-space(@class), " "), " diagnosis-symptom-card ")]'))
        );
        expectSame(1, count(TestDom::all($document, '//*[@data-diagnosis-step="1"]')));
    }

    public function testSelectedSymptomsRemainCheckedAfterValidationFailure(): void
    {
        $document = $this->renderForm([
            'errors' => ['Gejala masih belum sesuai, harap pilih gejala yang sesuai!'],
            'selected' => ['G002'],
        ]);
        $selected = TestDom::one($document, '//input[@id="gejala-G002"]');

        expectTrue($selected->hasAttribute('checked'), 'Gejala yang dipilih harus tetap tercentang.');
        expectTrue(
            strpos(TestDom::one($document, '//*[@id="diagnosis-errors"]')->textContent, 'Gejala masih belum sesuai') !== false,
            'Ringkasan kesalahan harus menjelaskan masalah.'
        );
    }

    public function testMemberResultRendersExplicitSaveAction(): void
    {
        $document = $this->renderResult(['authenticatedUser' => (object) ['id' => 7]]);
        $form = TestDom::one($document, '//form[@action="http://example.test/user/diagnosis"]');

        expectSame('post', $form->getAttribute('method'));
        expectSame(1, count(TestDom::all($document, '//form[@action="http://example.test/user/diagnosis"]//input[@name="csrf_test_name"]')));
        expectSame(
            'Simpan hasil ke riwayat',
            trim(TestDom::one($document, '//form[@action="http://example.test/user/diagnosis"]//button')->textContent)
        );
        expectSame(1, count(TestDom::all($document, '//*[@data-diagnosis-step="2"]')));
        expectTrue(
            strpos(TestDom::one($document, '//main')->textContent, 'bukan pengganti pemeriksaan dokter hewan') !== false,
            'Hasil harus tetap menyampaikan batasan saran sistem.'
        );
    }

    public function testGuestResultRendersLoginAction(): void
    {
        $document = $this->renderResult();

        expectSame(1, count(TestDom::all($document, '//main//a[@href="http://example.test/login" and contains(., "Masuk")]')));
        expectSame(0, count(TestDom::all($document, '//form[@action="http://example.test/user/diagnosis"]')));
    }

    private function renderForm(array $overrides = []): DOMDocument
    {
        $renderer = new ViewRenderer();

        return TestDom::document($renderer->renderPage('app/Views/diagnosis/index.php', array_merge([
            'title' => 'Diagnosis',
            'gejala' => [
                ['id_gejala' => 'G001', 'kategori' => 'Perilaku', 'gejala' => 'Tidak mau makan'],
                ['id_gejala' => 'G002', 'kategori' => 'Perilaku', 'gejala' => 'Terlihat lemas'],
                ['id_gejala' => 'G003', 'kategori' => 'Pencernaan', 'gejala' => 'Kotoran lebih lunak'],
            ],
            'errors' => [],
            'selected' => [],
        ], $overrides)));
    }

    private function renderResult(array $overrides = []): DOMDocument
    {
        $renderer = new ViewRenderer();

        return TestDom::document($renderer->renderPage('app/Views/diagnosis/hasil.php', array_merge([
            'title' => 'Hasil Diagnosis',
            'hasil' => (object) [
                'id_penyakit' => 'P001',
                'penyakit' => 'Gangguan pencernaan',
                'solusi' => 'Pantau asupan air dan konsultasikan kondisinya.',
                'obat' => 'Ikuti arahan dokter hewan.',
            ],
            'gejalaDetail' => 'Tidak mau makan, terlihat lemas',
        ], $overrides)));
    }
}
