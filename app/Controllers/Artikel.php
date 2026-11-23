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
        $data = [
            'title' => 'Artikel',
            'artikel' => $this->artikelModel->getPublished(),
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
