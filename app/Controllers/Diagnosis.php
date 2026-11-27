<?php

namespace App\Controllers;

use App\Models\decisionTreeModel;
use App\Models\gejalaModel;
use App\Models\penyakitModel;
use App\Services\DiagnosisRuleMatcher;

class Diagnosis extends BaseController
{
    protected $helpers = ['auth'];
    protected $gejalaModel;
    protected $decisionTreeModel;
    protected $penyakitModel;

    public function __construct()
    {
        $this->gejalaModel = new gejalaModel();
        $this->decisionTreeModel = new decisionTreeModel();
        $this->penyakitModel = new penyakitModel();
    }

    public function index()
    {
        return $this->showForm([]);
    }

    public function proses()
    {
        $selected = $this->normalizeSelection($this->request->getPost('gejala'));

        if ($selected === []) {
            return $this->showForm(['Harus memilih gejala minimal 1'], $selected);
        }

        $matcher = new DiagnosisRuleMatcher();
        $rule = $matcher->match($selected, $this->decisionTreeModel->findAll());

        if ($rule === null) {
            return $this->showForm(['Gejala masih belum sesuai, harap pilih gejala yang sesuai!'], $selected);
        }

        $penyakit = $this->penyakitModel->hasil((int) $rule['keputusan']);

        if ($penyakit === null) {
            return $this->showForm(['Penyakit untuk rule ini tidak ditemukan'], $selected);
        }

        return view('diagnosis/hasil', [
            'title' => 'Hasil Diagnosis',
            'hasil' => $penyakit,
            'gejalaDetail' => $rule['detail'] ?? '',
            'errors' => [],
        ]);
    }

    private function showForm(array $errors, array $selected = [])
    {
        return view('diagnosis/index', [
            'title' => 'Diagnosis',
            'gejala' => $this->gejalaModel->orderBy('kategori')->findAll(),
            'errors' => $errors,
            'selected' => $selected,
        ]);
    }

    private function normalizeSelection($value): array
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        return array_values(array_filter($value, static fn ($item): bool => (string) $item !== ''));
    }
}
