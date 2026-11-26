<?php

namespace App\Controllers;

use App\Models\listModel;
use App\Models\gejalaModel;
use App\Models\penyakitModel;
use App\Models\sampleModel;
use App\Models\miningModel;
use App\Models\decisionTreeModel;
use App\Models\artikelModel;
use App\Services\ArticleSlug;
use App\Services\DashboardChartData;
use App\Services\DecisionTreeGraph;


class Admin extends BaseController
{

    protected $db, $builder;
    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    public function __construct()
    {
        $this->db      = \Config\Database::connect();
        $this->builder = $this->db->table('users');
        $this->listModel = new listModel();
        $this->gejalaModel = new gejalaModel();
        $this->penyakitModel = new penyakitModel();
        $this->sampleModel = new sampleModel();
        $this->miningModel = new miningModel();
        $this->decisionTreeModel = new decisionTreeModel();
        $this->artikelModel = new artikelModel();
        $this->articleSlug = new ArticleSlug();
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'user' => $this->builder->countAllResults(),
            'gejala' => $this->gejalaModel->countGejala(),
            'penyakit' => $this->penyakitModel->countPenyakit(),
            'hasil' => $this->listModel->countAllHasil(),
            'chartData' => DashboardChartData::payload([
                'gejala' => $this->gejalaModel->countGejala(),
                'penyakit' => $this->penyakitModel->countPenyakit(),
                'sample' => $this->miningModel->countDataSample(),
            ], $this->listModel->countHasilPerPenyakit()),
        ];

        return view('user/index', $data);
    }

    public function profile()
    {
        $data['title'] = 'My Profile';
        return view('admin/profile', $data);
    }

    public function userlist()
    {
        $currentPage = $this->request->getVar('page_users') ? $this->request->getVar('page_users') : 1;

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $users = $this->listModel->search($keyword);
        } else {
            $users = $this->listModel;
        }

        $page = 8;
        $data = [
            'title' => 'User List',
            'users' => $users->select('users.id as userid, username, email, name')->join('auth_groups_users', 'auth_groups_users.user_id = users.id')->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')->paginate($page, 'users'),
            'pager' => $this->listModel->pager,
            'currentPage' => $currentPage,
            'page' => $page,
            'keyword' => $keyword,
        ];

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse(['html' => view('admin/fragments/userlist', $data)]);
        }

        return view('admin/userlist', $data);
    }

    public function gejala()
    {
        $currentPage = $this->request->getVar('page_gejala') ? $this->request->getVar('page_gejala') : 1;

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $gejala = $this->gejalaModel->search($keyword);
        } else {
            $gejala = $this->gejalaModel;
        }

        $page = 8;
        $data = [
            'title' => 'Data Gejala',
            'gejala' => $gejala->paginate($page, 'gejala'),
            'pager' => $this->gejalaModel->pager,
            'currentPage' => $currentPage,
            'page' => $page,
            'keyword' => $keyword,
        ];

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse(['html' => view('admin/fragments/gejala', $data)]);
        }

        return view('admin/gejala', $data);
    }

    public function tambahGejala()
    {
        $gejala = $this->request->getVar('gejala');
        $kategori = $this->request->getVar('kategori');

        if ($kategori || $gejala) {
            $val = $this->gejalaModel->validateGejala($kategori, $gejala);
            if ($val->countAllResults() > 0) {
                if ($this->request->isAJAX()) {
                    return $this->ajaxResponse(['message' => 'Gejala sudah ada!', 'errors' => ['gejala' => 'Gejala sudah ada!']], 422);
                }
                session()->setFlashdata('erorr', 'Gejala sudah ada!');
                return redirect()->to('admin/gejala')->withInput();
            }
        }

        $this->gejalaModel->save([
            'kategori' => $kategori,
            'gejala' => $gejala,
        ]);

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse([
                'message' => 'Gejala berhasil ditambahkan',
                'row' => [
                    'id_gejala' => $this->gejalaModel->getInsertID(),
                    'kategori' => $kategori,
                    'gejala' => $gejala,
                ],
            ]);
        }

        session()->setFlashdata('pesan', 'Gejala berhasil ditambahkan');
        return redirect()->to('admin/gejala');
    }

    public function updateGejala()
    {
        $idGejala = (int) $this->request->getVar('id_gejala');
        $gejala = $this->request->getVar('gejala');
        $kategori = $this->request->getVar('kategori');

        if ($kategori || $gejala) {
            $val = $this->gejalaModel->validateGejala($kategori, $gejala);
            if ($val->countAllResults() > 0) {
                if ($this->request->isAJAX()) {
                    return $this->ajaxResponse(['message' => 'Gejala sudah ada!', 'errors' => ['gejala' => 'Gejala sudah ada!']], 422);
                }
                session()->setFlashdata('update erorr', 'Gejala sudah ada!');
                return redirect()->to('admin/gejala')->withInput();
            }
        }

        $this->gejalaModel
            ->whereIn('id_gejala', [$idGejala])
            ->set([
                'kategori' => $kategori,
                'gejala' => $gejala,
            ])
            ->update();

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse([
                'message' => 'Gejala berhasil diubah',
                'row' => ['id_gejala' => $idGejala, 'kategori' => $kategori, 'gejala' => $gejala],
            ]);
        }

        session()->setFlashdata('pesan', 'Gejala berhasil diubah');
        return redirect()->to('admin/gejala');
    }

    public function deleteGejala()
    {
        $idGejala = (int) $this->request->getPost('id_gejala');

        foreach (['mining_sample', 'mining_entropy', 'entropy', 'mining_kasus'] as $table) {
            $this->db->table($table)->where('id_gejala', $idGejala)->delete();
        }

        $this->sampleModel->where('id_gejala', $idGejala)->delete();
        $this->gejalaModel->where('id_gejala', $idGejala)->delete();

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse([
                'message' => 'Gejala berhasil dihapus',
                'row' => ['id_gejala' => $idGejala],
            ]);
        }

        session()->setFlashdata('pesan', 'Gejala berhasil dihapus');
        return redirect()->to('admin/gejala');
    }

    public function penyakit()
    {
        $currentPage = $this->request->getVar('page_penyakit') ? $this->request->getVar('page_penyakit') : 1;

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $penyakit = $this->penyakitModel->search($keyword);
        } else {
            $penyakit = $this->penyakitModel;
        }

        $page = 8;
        $data = [
            'title' => 'Data Penyakit',
            'penyakit' => $penyakit->paginate($page, 'penyakit'),
            'pager' => $this->penyakitModel->pager,
            'currentPage' => $currentPage,
            'page' => $page,
            'keyword' => $keyword,
        ];

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse(['html' => view('admin/fragments/penyakit', $data)]);
        }

        return view('admin/penyakit', $data);
    }

    public function tambahPenyakit()
    {
        $penyakit = $this->request->getVar('penyakit');
        $obat = $this->request->getVar('obat');
        $solusi = $this->request->getVar('solusi');

        if ($penyakit) {
            $val = $this->penyakitModel->validatePenyakit($penyakit);
            if ($val->countAllResults() > 0) {
                if ($this->request->isAJAX()) {
                    return $this->ajaxResponse(['message' => 'Penyakit sudah ada!', 'errors' => ['penyakit' => 'Penyakit sudah ada!']], 422);
                }
                session()->setFlashdata('erorr-tambah-penyakit', 'Penyakit sudah ada!');
                return redirect()->to('admin/penyakit')->withInput();
            }
        }

        $this->penyakitModel->save([
            'penyakit' => $penyakit,
            'obat' => $obat,
            'solusi' => $solusi,
        ]);

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse([
                'message' => 'Penyakit berhasil ditambahkan',
                'row' => [
                    'id_penyakit' => $this->penyakitModel->getInsertID(),
                    'penyakit' => $penyakit,
                    'obat' => $obat,
                    'solusi' => $solusi,
                ],
            ]);
        }

        session()->setFlashdata('pesan', 'Penyakit berhasil ditambahkan');
        return redirect()->to('admin/penyakit');
    }

    public function updatePenyakit()
    {
        $idPenyakit = (int) $this->request->getVar('id_penyakit');
        $obat = $this->request->getVar('obat');
        $solusi = $this->request->getVar('solusi');

        $this->penyakitModel
            ->whereIn('id_penyakit', [$idPenyakit])
            ->set([
                'obat' => $obat,
                'solusi' => $solusi,
            ])
            ->update();

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse([
                'message' => 'Penyakit berhasil diubah!',
                'row' => ['id_penyakit' => $idPenyakit, 'obat' => $obat, 'solusi' => $solusi],
            ]);
        }

        session()->setFlashdata('pesan', 'Penyakit berhasil diubah!');
        return redirect()->to('admin/penyakit');
    }

    public function deletePenyakit()
    {
        $idPenyakit = (int) $this->request->getPost('id_penyakit');

        $this->miningModel->clearMiningEntropy();
        $this->db->table('hasil')->where('id_penyakit', $idPenyakit)->delete();
        $this->db->table('mining_sample')->where('id_penyakit', $idPenyakit)->delete();
        $this->decisionTreeModel->where('keputusan', $idPenyakit)->delete();
        $this->sampleModel->where('id_penyakit', $idPenyakit)->delete();
        $this->penyakitModel->where('id_penyakit', $idPenyakit)->delete();

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse([
                'message' => 'Penyakit berhasil dihapus',
                'row' => ['id_penyakit' => $idPenyakit],
            ]);
        }

        session()->setFlashdata('pesan', 'Penyakit berhasil dihapus');
        return redirect()->to('admin/penyakit');
    }

    public function dataSample()
    {
        $currentPage = $this->request->getVar('page_sample') ? $this->request->getVar('page_sample') : 1;

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $sample = $this->sampleModel->search($keyword);
        } else {
            $sample = $this->sampleModel;
        }

        $page = 8;
        $data = [
            'title' => 'Data Sample',
            'sample' => $sample->select('data_sample.id_sample, data_sample.id_gejala,data_sample.id_penyakit, data_gejala.kategori, data_gejala.gejala, data_penyakit.penyakit')
                ->join('data_gejala', 'data_gejala.id_gejala = data_sample.id_gejala')->join('data_penyakit', 'data_penyakit.id_penyakit = data_sample.id_penyakit')->paginate($page, 'sample'),
            'pager' => $this->sampleModel->pager,
            'currentPage' => $currentPage,
            'page' => $page,
            'keyword' => $keyword,
        ];
        $data['gejala'] = $this->gejalaModel->findall();
        $data['penyakit'] = $this->penyakitModel->findall();

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse(['html' => view('admin/fragments/sample', $data)]);
        }

        return view('admin/sample', $data);
    }

    public function tambahSample()
    {
        $gejala = (int) $this->request->getVar('sampleGejala');
        $penyakit = (int) $this->request->getVar('samplePenyakit');

        $this->sampleModel->save([
            'id_gejala' => $gejala,
            'id_penyakit' => $penyakit,
        ]);

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse([
                'message' => 'Sample berhasil ditambahkan, harap click tombol Start Mining untuk proses data mining!',
                'row' => [
                    'id_sample' => $this->sampleModel->getInsertID(),
                    'id_gejala' => $gejala,
                    'id_penyakit' => $penyakit,
                ],
            ]);
        }

        session()->setFlashdata('pesanSample', 'Sample berhasil ditambahkan, harap click tombol Start Mining untuk proses data mining!');
        return redirect()->to('admin/datasample');
    }

    public function updateSample()
    {
        $idSample = (int) $this->request->getVar('id_sample');
        $gejala = (int) $this->request->getVar('sampleGejala');
        $penyakit = (int) $this->request->getVar('samplePenyakit');

        $this->sampleModel
            ->whereIn('id_sample', [$idSample])
            ->set([
                'id_gejala' => $gejala,
                'id_penyakit' => $penyakit,
            ])
            ->update();

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse([
                'message' => 'Data sample berhasil diubah, harap click tombol Start Mining untuk proses data mining!',
                'row' => ['id_sample' => $idSample, 'id_gejala' => $gejala, 'id_penyakit' => $penyakit],
            ]);
        }

        session()->setFlashdata('pesanSample', 'Data sample berhasil diubah, harap click tombol Start Mining untuk proses data mining!');
        return redirect()->to('admin/datasample');
    }

    public function deleteSample()
    {
        $idSample = (int) $this->request->getVar('id_sample');

        $this->sampleModel->where('id_sample', [$idSample])->delete();

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse([
                'message' => 'Sample berhasil dihapus, harap click tombol Start Mining untuk proses data mining!',
                'row' => ['id_sample' => $idSample],
            ]);
        }

        session()->setFlashdata('pesanSample', 'Sample berhasil dihapus, harap click tombol Start Mining untuk proses data mining!');

        return redirect()->to('admin/datasample');
    }

    public function detail($id = 0)
    {
        $data['title'] = 'User Detail';

        $this->builder->select('users.id as userid, username, email, name, fullname, user_image');
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $this->builder->where('users.id', $id);
        $query = $this->builder->get();

        $data['user'] = $query->getRow();

        if (empty($data['user'])) {
            return redirect()->to('/admin');
        }

        return view('admin/detail', $data);
    }

    public function decisionTree()
    {
        $currentPage = $this->request->getVar('page_decision_tree') ? $this->request->getVar('page_decision_tree') : 1;
        $keyword = $this->request->getVar('keyword');
        $decisiontree = $keyword ? $this->decisionTreeModel->search($keyword) : $this->decisionTreeModel;

        $page = 8;
        $rows = $this->db->query(
            "SELECT dt.akar, dt.parent, dt.detail, dt.keputusan, p.penyakit
             FROM decision_tree dt JOIN data_penyakit p ON p.id_penyakit = dt.keputusan
             ORDER BY dt.id"
        )->getResultArray();

        $symptomLabels = [];
        foreach ($this->db->query("SELECT id_gejala, kategori, gejala FROM data_gejala")->getResultArray() as $g) {
            $symptomLabels[$g['id_gejala']] = trim($g['kategori'] . ' ' . $g['gejala']);
        }
        $graph = new DecisionTreeGraph($rows, $symptomLabels);

        $data = [
            'title' => 'Decision Tree',
            'decisiontree' => $decisiontree->select('decision_tree.detail, decision_tree.akar, decision_tree.keputusan, data_penyakit.penyakit')
                ->join('data_penyakit', 'data_penyakit.id_penyakit = decision_tree.keputusan')->paginate($page, 'decision_tree'),
            'pager' => $this->decisionTreeModel->pager,
            'currentPage' => $currentPage,
            'page' => $page,
            'keyword' => $keyword,
            'svg' => $graph->toSvg(),
            'treeList' => $graph->toAccessibleList(),
        ];

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse(['html' => view('admin/fragments/decisiontree', $data)]);
        }

        return view('admin/decisiontree', $data);
    }

    public function artikel()
    {
        $keyword = $this->request->getVar('keyword');
        $query = $this->artikelModel;
        if ($keyword) {
            $query = $this->artikelModel->like('judul', $keyword)->orLike('slug', $keyword);
        }

        $articles = $query->paginate(8);
        $categories = $this->artikelModel->getCategoryOptions();
        foreach ($articles as &$article) {
            $categoryIds = $this->artikelModel->getCategoryIds((int) $article['id']);
            $firstCategory = $categoryIds[0] ?? 0;
            $article['kategori_id'] = $firstCategory;
            $article['nama_kategori'] = $categories[$firstCategory] ?? '';
        }
        unset($article);

        $data = [
            'title' => 'Data Artikel',
            'artikel' => $articles,
            'pager' => $this->artikelModel->pager,
            'kategori' => $categories,
            'keyword' => $keyword,
        ];

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse(['html' => view('admin/fragments/artikel', $data)]);
        }

        return view('admin/artikel', $data);
    }

    public function tambahArtikel()
    {
        $judul = trim((string) $this->request->getVar('judul'));
        $kategoriId = (int) $this->request->getVar('kategori_id');
        $rules = [
            'judul' => 'required|max_length[255]',
            'slug' => 'permit_empty|alpha_dash',
            'ringkasan' => 'required',
            'konten' => 'required',
            'status' => 'required|in_list[publish,draft]',
            'kategori_id' => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->ajaxResponse(['message' => 'Periksa kembali input artikel.', 'errors' => $this->validator->getErrors()], 422);
            }
            session()->setFlashdata('erorr', 'Periksa kembali input artikel.');
            return redirect()->to('admin/artikel')->withInput();
        }

        $status = (string) $this->request->getVar('status');
        $slug = trim((string) $this->request->getVar('slug'));
        if ($slug === '') {
            $slug = $this->articleSlug->unique($this->articleSlug->fromJudul($judul), function (string $candidate): bool {
                return $this->artikelModel->slugExists($candidate);
            });
        } elseif ($this->artikelModel->slugExists($slug)) {
            if ($this->request->isAJAX()) {
                return $this->ajaxResponse(['message' => 'Slug artikel sudah ada!', 'errors' => ['slug' => 'Slug artikel sudah ada!']], 422);
            }
            session()->setFlashdata('erorr', 'Slug artikel sudah ada!');
            return redirect()->to('admin/artikel')->withInput();
        }

        $gambar = trim((string) $this->request->getVar('gambar'));
        $fileGambar = $this->request->getFile('gambar_file');
        if ($fileGambar !== null && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $newName = $fileGambar->getRandomName();
            $fileGambar->move(FCPATH . 'uploads/artikel', $newName);
            $gambar = base_url('uploads/artikel/' . $newName);
        }

        $id = $this->saveArticle([
            'judul' => $judul,
            'slug' => $slug,
            'ringkasan' => (string) $this->request->getVar('ringkasan'),
            'konten' => (string) $this->request->getVar('konten'),
            'status' => $status,
            'published_at' => $this->publishedAt($status, (string) $this->request->getVar('published_at')),
            'gambar' => $gambar,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ], $kategoriId);

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse(['message' => 'Artikel berhasil ditambahkan', 'row' => $this->articleRow($id)]);
        }

        session()->setFlashdata('pesan', 'Artikel berhasil ditambahkan');
        return redirect()->to('admin/artikel');
    }

    public function updateArtikel()
    {
        $id = (int) $this->request->getVar('id');
        $judul = trim((string) $this->request->getVar('judul'));
        $kategoriId = (int) $this->request->getVar('kategori_id');
        $rules = [
            'id' => 'required|integer|greater_than[0]',
            'judul' => 'required|max_length[255]',
            'slug' => 'permit_empty|alpha_dash',
            'ringkasan' => 'required',
            'konten' => 'required',
            'status' => 'required|in_list[publish,draft]',
            'kategori_id' => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->ajaxResponse(['message' => 'Periksa kembali input artikel.', 'errors' => $this->validator->getErrors()], 422);
            }
            session()->setFlashdata('update erorr', 'Periksa kembali input artikel.');
            return redirect()->to('admin/artikel')->withInput();
        }

        $status = (string) $this->request->getVar('status');
        $slug = trim((string) $this->request->getVar('slug'));
        if ($slug === '') {
            $slug = $this->articleSlug->unique($this->articleSlug->fromJudul($judul), function (string $candidate) use ($id): bool {
                return $this->artikelModel->slugExists($candidate, $id);
            });
        } elseif ($this->artikelModel->slugExists($slug, $id)) {
            if ($this->request->isAJAX()) {
                return $this->ajaxResponse(['message' => 'Slug artikel sudah ada!', 'errors' => ['slug' => 'Slug artikel sudah ada!']], 422);
            }
            session()->setFlashdata('update erorr', 'Slug artikel sudah ada!');
            return redirect()->to('admin/artikel')->withInput();
        }

        $existing = $this->artikelModel->findById($id);
        $gambar = trim((string) $this->request->getVar('gambar'));
        $fileGambar = $this->request->getFile('gambar_file');
        if ($fileGambar !== null && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $newName = $fileGambar->getRandomName();
            $fileGambar->move(FCPATH . 'uploads/artikel', $newName);
            $gambar = base_url('uploads/artikel/' . $newName);
        } elseif ($gambar === '' && !empty($existing['gambar'])) {
            $gambar = $existing['gambar'];
        }

        $this->saveArticle([
            'judul' => $judul,
            'slug' => $slug,
            'ringkasan' => (string) $this->request->getVar('ringkasan'),
            'konten' => (string) $this->request->getVar('konten'),
            'status' => $status,
            'published_at' => $this->publishedAt($status, (string) $this->request->getVar('published_at')),
            'gambar' => $gambar,
            'updated_at' => date('Y-m-d H:i:s'),
        ], $kategoriId, $id);

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse(['message' => 'Artikel berhasil diubah', 'row' => $this->articleRow($id)]);
        }

        session()->setFlashdata('pesan', 'Artikel berhasil diubah');
        return redirect()->to('admin/artikel');
    }

    public function deleteArtikel()
    {
        $id = (int) $this->request->getVar('id');
        $this->db->table('artikel_kategori')->where('artikel_id', $id)->delete();
        $this->artikelModel->where('id', $id)->delete();

        if ($this->request->isAJAX()) {
            return $this->ajaxResponse(['message' => 'Artikel berhasil dihapus', 'row' => ['id' => $id]]);
        }

        session()->setFlashdata('pesan', 'Artikel berhasil dihapus');
        return redirect()->to('admin/artikel');
    }

    private function saveArticle(array $data, int $kategoriId, int $id = 0): int
    {
        if ($id > 0) {
            $this->artikelModel->where('id', $id)->set($data)->update();
        } else {
            $this->artikelModel->save($data);
            $id = (int) $this->artikelModel->getInsertID();
        }

        $this->artikelModel->saveCategories($id, [$kategoriId]);

        return $id;
    }

    private function publishedAt(string $status, string $publishedAt): ?string
    {
        if ($status !== 'publish') {
            return null;
        }

        return $publishedAt !== '' ? $publishedAt : date('Y-m-d H:i:s');
    }

    private function articleRow(int $id): array
    {
        $article = $this->artikelModel->findById($id) ?? [];
        $categoryIds = $this->artikelModel->getCategoryIds($id);
        $categories = $this->artikelModel->getCategoryOptions();
        $firstCategory = $categoryIds[0] ?? 0;

        return [
            'id' => $id,
            'judul' => $article['judul'] ?? '',
            'slug' => $article['slug'] ?? '',
            'status' => $article['status'] ?? '',
            'kategori' => $categories[$firstCategory] ?? '',
            'gambar' => $article['gambar'] ?? '',
        ];
    }
}