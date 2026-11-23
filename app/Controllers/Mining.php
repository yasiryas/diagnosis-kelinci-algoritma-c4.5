<?php

namespace App\Controllers;

use App\Models\miningModel;

class Mining extends BaseController
{
    protected $db;
    protected $miningModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->miningModel = new miningModel();
    }

    public function hitung()
    {
        $dataset = $this->buildDataset();
        $atributCount = count($this->getAttributeCategories($dataset['instances']));
        $calc = $this->calculateC45($dataset['instances']);

        $data = [
            'title' => 'Data Mining - Perhitungan C4.5',
            'atributCount' => $atributCount,
            'jumlahkasus' => count($dataset['instances']),
            'instances' => $dataset['instances'],
            'gejalaInfo' => $dataset['gejalaInfo'],
            'penyakitInfo' => $dataset['penyakitInfo'],
            'calc' => $calc,
        ];

        return view('admin/hasilmining', $data);
    }

    public function mining()
    {
        $this->miningModel->clearDecisionTree();
        $this->miningModel->clearMiningSample();

        $dataset = $this->buildDataset();
        $instances = $dataset['instances'];
        $attributes = $this->getAttributeCategories($instances);

        $tree = $this->buildTree($instances, $attributes, $dataset);
        $this->saveTree($tree, $dataset);

        $this->storeRootCalculations($instances);

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse(['message' => 'Pohon keputusan berhasil dibuat']);
        }

        return redirect()->to('/admin/decisiontree');
    }

    public function kosong()
    {
        $this->miningModel->clearDecisionTree();
        $this->miningModel->clearMiningSample();
        $this->miningModel->startMiningSample();

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse(['message' => 'Data sample berhasil dikosongkan']);
        }

        return redirect()->to('/admin/datasample');
    }

    private function buildDataset(): array
    {
        $rows = $this->db->query(
            "SELECT s.id_sample, s.id_gejala, s.id_penyakit, g.kategori, g.gejala, p.penyakit
             FROM data_sample s
             JOIN data_gejala g ON s.id_gejala = g.id_gejala
             JOIN data_penyakit p ON s.id_penyakit = p.id_penyakit
             ORDER BY s.id_penyakit, s.id_sample"
        )->getResultArray();

        $gejalaInfo = [];
        $penyakitInfo = [];
        $byPenyakit = [];
        foreach ($rows as $r) {
            $kategori = trim($r['kategori']);
            $gejalaInfo[$r['id_gejala']] = [
                'id_gejala' => $r['id_gejala'],
                'kategori' => $kategori,
                'gejala' => $r['gejala'],
                'label' => $kategori . ' ' . $r['gejala'],
            ];
            $penyakitInfo[$r['id_penyakit']] = $r['penyakit'];
            $byPenyakit[$r['id_penyakit']]['attrs'][$kategori][$r['id_gejala']] =
                ($byPenyakit[$r['id_penyakit']]['attrs'][$kategori][$r['id_gejala']] ?? 0) + 1;
        }

        $instances = [];
        foreach ($byPenyakit as $pid => $info) {
            $n = $this->countCasesForPenyakit($info['attrs']);
            $attrAssignments = [];
            foreach ($info['attrs'] as $kategori => $values) {
                $assign = [];
                foreach ($values as $gid => $count) {
                    for ($i = 0; $i < $count; $i++) {
                        $assign[] = $gid;
                    }
                }
                while (count($assign) < $n) {
                    $assign[] = null;
                }
                $attrAssignments[$kategori] = $assign;
            }
            for ($i = 0; $i < $n; $i++) {
                $inst = ['penyakit' => (int) $pid, 'attrs' => []];
                foreach ($attrAssignments as $kategori => $assign) {
                    $inst['attrs'][$kategori] = $assign[$i];
                }
                $instances[] = $inst;
            }
        }

        return [
            'instances' => $instances,
            'gejalaInfo' => $gejalaInfo,
            'penyakitInfo' => $penyakitInfo,
        ];
    }

    private function countCasesForPenyakit(array $attrs): int
    {
        $n = 0;
        foreach ($attrs as $values) {
            $n = max($n, array_sum($values));
        }
        return $n;
    }

    private function getAttributeCategories(array $instances): array
    {
        $cats = [];
        foreach ($instances as $inst) {
            foreach ($inst['attrs'] as $kategori => $gid) {
                if ($gid !== null) {
                    $cats[$kategori] = true;
                }
            }
        }
        $categories = array_keys($cats);
        sort($categories);
        return $categories;
    }

    private function entropy(array $instances): float
    {
        $total = count($instances);
        if ($total === 0) {
            return 0.0;
        }
        $counts = [];
        foreach ($instances as $inst) {
            $counts[$inst['penyakit']] = ($counts[$inst['penyakit']] ?? 0) + 1;
        }
        $entropy = 0.0;
        foreach ($counts as $count) {
            $p = $count / $total;
            if ($p > 0) {
                $entropy -= $p * log($p, 2);
            }
        }
        return $entropy;
    }

    private function classDistribution(array $instances): array
    {
        $dist = [];
        foreach ($instances as $inst) {
            $dist[$inst['penyakit']] = ($dist[$inst['penyakit']] ?? 0) + 1;
        }
        return $dist;
    }

    private function calculateC45(array $instances, ?array $attributes = null): array
    {
        if ($attributes === null) {
            $attributes = $this->getAttributeCategories($instances);
        }
        $totalEntropy = $this->entropy($instances);
        $total = count($instances);
        $result = [
            'totalEntropy' => $totalEntropy,
            'total' => $total,
            'distribution' => $this->classDistribution($instances),
            'attributes' => [],
        ];

        foreach ($attributes as $attr) {
            $groups = [];
            $knownCount = 0;
            foreach ($instances as $inst) {
                $gid = $inst['attrs'][$attr] ?? null;
                if ($gid === null) {
                    continue;
                }
                $groups[$gid][] = $inst;
                $knownCount++;
            }
            if ($knownCount === 0 || count($groups) <= 1) {
                $result['attributes'][$attr] = [
                    'gain' => 0.0,
                    'splitInfo' => 0.0,
                    'gainRatio' => 0.0,
                    'info' => $totalEntropy,
                    'values' => [],
                ];
                continue;
            }

            $info = 0.0;
            $splitInfo = 0.0;
            $values = [];
            foreach ($groups as $gid => $subset) {
                $subsetCount = count($subset);
                $p = $subsetCount / $knownCount;
                $subsetEntropy = $this->entropy($subset);
                $info += $p * $subsetEntropy;
                if ($p > 0) {
                    $splitInfo -= $p * log($p, 2);
                }
                $values[$gid] = [
                    'count' => $subsetCount,
                    'entropy' => $subsetEntropy,
                    'distribution' => $this->classDistribution($subset),
                ];
            }
            $gain = $totalEntropy - $info;
            $gainRatio = $splitInfo > 0 ? $gain / $splitInfo : 0.0;
            $result['attributes'][$attr] = [
                'gain' => $gain,
                'splitInfo' => $splitInfo,
                'gainRatio' => $gainRatio,
                'info' => $info,
                'values' => $values,
            ];
        }

        return $result;
    }

    private function buildTree(array $instances, array $attributes, array $dataset): array
    {
        $distribution = $this->classDistribution($instances);
        arsort($distribution);
        $majority = (int) array_key_first($distribution);

        if (count($distribution) === 1) {
            return ['type' => 'leaf', 'penyakit' => (int) array_key_first($distribution)];
        }
        if (empty($attributes) || count($instances) === 0) {
            return ['type' => 'leaf', 'penyakit' => $majority];
        }

        $calc = $this->calculateC45($instances, $attributes);
        $bestAttr = null;
        $best = null;
        foreach ($calc['attributes'] as $attr => $info) {
            if (empty($info['values'])) {
                continue;
            }
            if ($best === null
                || $info['gainRatio'] > $best['gainRatio']
                || ($info['gainRatio'] == $best['gainRatio'] && $info['gain'] > $best['gain'])) {
                $best = $info;
                $bestAttr = $attr;
            }
        }

        if ($bestAttr === null || $best['gain'] <= 0) {
            return ['type' => 'leaf', 'penyakit' => $majority];
        }

        $node = [
            'type' => 'node',
            'attribute' => $bestAttr,
            'majority' => $majority,
            'branches' => [],
        ];
        $remainingAttrs = array_values(array_filter($attributes, static function ($a) use ($bestAttr) {
            return $a !== $bestAttr;
        }));

        foreach ($best['values'] as $gid => $vinfo) {
            $subset = [];
            foreach ($instances as $inst) {
                if (($inst['attrs'][$bestAttr] ?? null) == $gid) {
                    $subset[] = $inst;
                }
            }
            $node['branches'][$gid] = $this->buildTree($subset, $remainingAttrs, $dataset);
        }

        return $node;
    }

    private function saveTree(array $tree, array $dataset): void
    {
        $this->persistNode($tree, [], []);
    }

    private function persistNode(array $node, array $parentIds, array $detailParts): void
    {
        if ($node['type'] === 'leaf') {
            if (empty($parentIds)) {
                return;
            }
            $this->db->query(
                "INSERT INTO decision_tree (id, parent, detail, akar, keputusan) VALUES (NULL, ?, ?, ?, ?)",
                [
                    implode(' ', $parentIds),
                    implode(' dan ', $detailParts),
                    $this->lastCategory($detailParts),
                    $node['penyakit'],
                ]
            );
            return;
        }

        foreach ($node['branches'] as $gid => $child) {
            $gejalaInfo = $this->getGejalaInfo((int) $gid);
            $newParentIds = array_merge($parentIds, [(int) $gid]);
            $newDetailParts = array_merge($detailParts, [$gejalaInfo['label']]);
            $this->persistNode($child, $newParentIds, $newDetailParts);
        }
    }

    private function getGejalaInfo(int $gid): array
    {
        static $cache = [];
        if (isset($cache[$gid])) {
            return $cache[$gid];
        }
        $row = $this->db->query(
            "SELECT id_gejala, kategori, gejala FROM data_gejala WHERE id_gejala = ?",
            [$gid]
        )->getRowArray();
        $kategori = trim($row['kategori'] ?? '');
        $cache[$gid] = [
            'id_gejala' => $gid,
            'kategori' => $kategori,
            'gejala' => $row['gejala'] ?? '',
            'label' => $kategori . ' ' . ($row['gejala'] ?? ''),
        ];
        return $cache[$gid];
    }

    private function lastCategory(array $detailParts): string
    {
        if (empty($detailParts)) {
            return '';
        }
        $last = end($detailParts);
        $parts = explode(' ', $last, 2);
        return $parts[0] ?? $last;
    }

    private function storeRootCalculations(array $instances): void
    {
        $this->miningModel->clearEntropy();
        $this->miningModel->clearGain();
        $this->miningModel->clearGainRatio();
        $this->miningModel->clearMiningGain();
        $this->miningModel->clearMiningSplitInfo();
        $this->miningModel->clearMiningEntropy();
        $this->miningModel->clearMiningKasus();

        $calc = $this->calculateC45($instances);
        foreach ($calc['attributes'] as $attr => $info) {
            $this->db->query("INSERT INTO gain (id, kategori, gain) VALUES (NULL, ?, ?)", [$attr, $info['gain']]);
            $this->db->query("INSERT INTO gain_ratio (id, kategori, gain_ratio) VALUES (NULL, ?, ?)", [$attr, $info['gainRatio']]);
            foreach ($info['values'] as $gid => $vinfo) {
                $this->db->query("INSERT INTO entropy (id, id_gejala, entropy) VALUES (NULL, ?, ?)", [$gid, $vinfo['entropy']]);
            }
        }
    }
}
