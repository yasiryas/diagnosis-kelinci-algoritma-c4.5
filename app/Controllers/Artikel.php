<?php

namespace App\Controllers;

use App\Models\artikelModel;

class Artikel extends BaseController
{
    protected $artikelModel;

    public function __construct()
    {
        $this->artikelModel = new artikelModel();
    }

    public function index()
    {
        $keyword = trim((string) $this->request->getVar('keyword'));
        $kategoriSlug = trim((string) $this->request->getVar('kategori'));

        $data = [
            'title' => 'Artikel Panduan Kelinci',
            'artikel' => $this->artikelModel->getPublished(0, $keyword, $kategoriSlug),
            'kategoriList' => $this->artikelModel->getCategories(),
            'currentCategory' => $kategoriSlug,
            'keyword' => $keyword,
        ];

        return view('artikel/index', $data);
    }

    public function show(string $slug)
    {
        $item = $this->artikelModel->getPublishedBySlug($slug);
        if ($item === null) {
            $this->response->setStatusCode(404);
        }

        return view('artikel/show', [
            'title' => $item['judul'] ?? 'Artikel tidak ditemukan',
            'item' => $item,
        ]);
    }
}
