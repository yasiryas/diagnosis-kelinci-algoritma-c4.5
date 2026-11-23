<?php

declare(strict_types=1);

final class DecisionTreeGraphTest
{
    public function testGroupsRowsByAkarIntoMultipleRoots(): void
    {
        $graph = new App\Services\DecisionTreeGraph([
            ['akar' => '1', 'parent' => '2 3', 'penyakit' => 'Coccidiosis'],
            ['akar' => '4', 'parent' => '5', 'penyakit' => 'Snuffles'],
        ], [1 => 'Makan', 2 => 'Diare', 3 => 'Lemah', 4 => 'Napas', 5 => 'Bersin']);

        $roots = array_values(array_filter($graph->nodes(), static fn ($n): bool => $n['kind'] === 'root'));

        expectSame(2, count($roots), 'Tiap nilai akar harus menjadi root tersendiri.');
        expectSame('Makan', $roots[0]['label'], 'Label root harus memakai peta gejala.');
    }

    public function testReusesSymptomNodesAcrossRules(): void
    {
        $graph = new App\Services\DecisionTreeGraph([
            ['akar' => '1', 'parent' => '2', 'penyakit' => 'Coccidiosis'],
            ['akar' => '1', 'parent' => '2 3', 'penyakit' => 'Snuffles'],
        ]);

        expectSame(2, count(array_filter($graph->nodes(), static fn ($n): bool => $n['kind'] === 'symptom')), 'Node gejala yang sama harus dipakai ulang.');
        expectSame(2, count(array_filter($graph->nodes(), static fn ($n): bool => $n['kind'] === 'disease')), 'Tiap rule harus punya daun penyakit.');
    }

    public function testEmptyParentAttachesLeafDirectlyToRoot(): void
    {
        $graph = new App\Services\DecisionTreeGraph([
            ['akar' => '1', 'parent' => '', 'penyakit' => 'Coccidiosis'],
        ]);

        expectSame(2, count($graph->nodes()), 'Rule tanpa gejala harus punya root dan daun.');
        expectSame(1, count($graph->edges()), 'Root harus langsung terhubung ke daun.');
    }

    public function testTrimsAkarBeforeUsingRootKeyAndLabel(): void
    {
        $graph = new App\Services\DecisionTreeGraph([
            ['akar' => '1 ', 'parent' => '2', 'penyakit' => 'Coccidiosis'],
            ['akar' => '1', 'parent' => '3', 'penyakit' => 'Snuffles'],
        ], [1 => 'Makan', 2 => 'Diare', 3 => 'Lemah']);

        $roots = array_values(array_filter($graph->nodes(), static fn ($n): bool => $n['kind'] === 'root'));

        expectSame(1, count($roots), 'Akar dengan spasi harus memakai satu root yang sama.');
        expectSame('Makan', $roots[0]['label'], 'Label root harus memakai peta gejala setelah trim.');
    }

    public function testRendersFocusableSvgWithTitleAndAccessibleList(): void
    {
        $graph = new App\Services\DecisionTreeGraph([
            ['akar' => '1', 'parent' => '2', 'penyakit' => 'Coccidiosis & Vermin'],
        ]);

        $svg = $graph->toSvg();
        $list = $graph->toAccessibleList();

        expectTrue(strpos($svg, '<svg') !== false, 'Harus merender svg inline.');
        expectTrue(strpos($svg, '<path') !== false, 'Harus merender edge sebagai path.');
        expectTrue(strpos($svg, '<g tabindex="0"') !== false, 'Node harus bisa difokus.');
        expectTrue(strpos($svg, '<title>') !== false, 'Tiap node harus punya title.');
        expectTrue(strpos($svg, 'Coccidiosis &amp; Vermin') !== false, 'Label harus di-escape.');
        expectTrue(strpos($list, '<ul>') !== false && strpos($list, 'sr-only') !== false, 'Harus ada fallback daftar untuk pembaca layar.');
    }

    public function testEmptyRowsRenderEmptyState(): void
    {
        $graph = new App\Services\DecisionTreeGraph([]);

        expectSame([], $graph->nodes(), 'Tidak ada node untuk rule kosong.');
        expectSame([], $graph->edges(), 'Tidak ada edge untuk rule kosong.');
        expectTrue(strpos($graph->toSvg(), 'Tidak ada pohon') !== false, 'SVG kosong harus punya pesan.');
        expectSame('', $graph->toAccessibleList(), 'Daftar kosong tidak perlu output.');
    }

    public function testEmptyStateTextHasCoordinatesAndAdequateWidth(): void
    {
        $svg = (new App\Services\DecisionTreeGraph([]))->toSvg();

        expectTrue(
            strpos($svg, '<text x="10" y="28">Tidak ada pohon. Jalankan Proses Mining.</text>') !== false,
            'Pesan empty state harus punya koordinat x dan y.'
        );
        preg_match('/viewBox="0 0 (\d+) (\d+)"/', $svg, $matches);
        expectTrue(isset($matches[1]), 'SVG harus punya viewBox.');
        expectTrue((int) $matches[1] >= 400, 'Lebar viewBox harus cukup untuk seluruh pesan.');
    }
}
