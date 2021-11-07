<?php

namespace App\Controllers;

use App\Models\listModel;
use App\Models\gejalaModel;
use App\Models\penyakitModel;
use App\Models\sampleModel;
use App\Models\decisionTreeModel;


class admin extends BaseController
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
        $this->decisionTreeModel = new decisionTreeModel();
    }

    public function profile()
    {
        $data['title'] = 'My Profile';
        return view('admin/index', $data);
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
        ];

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
        ];

        return view('admin/gejala', $data);
    }

    public function tambahGejala()
    {
        //validate gejala and kategori
        $gejala = $this->request->getVar('gejala');
        $kategori = $this->request->getVar('kategori');

        if ($kategori || $gejala) {
            $val = $this->gejalaModel->validateGejala($kategori, $gejala);
            if ($val->countAllResults() > 0) {
                session()->setFlashdata('erorr', 'Gejala sudah ada!');
                return redirect()->to('/admin/gejala')->withInput();
            }
        }
        //end validate

        $this->gejalaModel->save([
            'kategori' => $this->request->getVar('kategori'),
            'gejala' => $this->request->getVar('gejala'),
        ]);
        session()->setFlashdata('pesan', 'Gejala berhasil ditambahkan');
        return redirect()->to('/admin/gejala');
    }

    public function updateGejala()
    {
        //validate gejala and kategori
        $gejala = $this->request->getVar('gejala');
        $kategori = $this->request->getVar('kategori');

        if ($kategori || $gejala) {
            $val = $this->gejalaModel->validateGejala($kategori, $gejala);
            if ($val->countAllResults() > 0) {
                session()->setFlashdata('update erorr', 'Gejala sudah ada!');
                return redirect()->to('/admin/gejala')->withInput();
            }
        }
        //end validate

        //query update
        $this->gejalaModel
            ->whereIn('id_gejala', [$this->request->getVar('id_gejala')])
            ->set([
                'kategori' => $this->request->getVar('kategori'),
                'gejala' => $this->request->getVar('gejala'),
            ])
            ->update();

        session()->setFlashdata('pesan', 'Gejala berhasil diubah');
        return redirect()->to('/admin/gejala');
    }

    public function deleteGejala()
    {
        $this->gejalaModel->where('id_gejala', [$this->request->getVar('id_gejala')])->delete();

        session()->setFlashdata('pesan', 'Gejala berhasil dihapus');
        return redirect()->to('/admin/gejala');
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
            'penyakit' => $penyakit->paginate($page, 'penyait'),
            'pager' => $this->penyakitModel->pager,
            'currentPage' => $currentPage,
            'page' => $page,
        ];

        return view('admin/penyakit', $data);
    }

    public function tambahPenyakit()
    {
        //validate gejala and kategori
        $penyakit = $this->request->getVar('penyakit');
        $obat = $this->request->getVar('obat');
        $solusi = $this->request->getVar('solusi');

        if ($penyakit) {
            $val = $this->penyakitModel->validatePenyakit($penyakit);
            if ($val->countAllResults() > 0) {
                session()->setFlashdata('erorr-tambah-penyakit', 'Penyakit sudah ada!');
                return redirect()->to('/admin/penyakit')->withInput();
            }
        }
        //end validate

        $this->penyakitModel->save([
            'penyakit' => $penyakit,
            'obat' => $obat,
            'solusi' => $solusi,
        ]);

        session()->setFlashdata('pesan', 'Penyakit berhasil ditambahkan');
        return redirect()->to('/admin/penyakit');
    }

    public function updatePenyakit()
    {
        //query update
        $this->penyakitModel
            ->whereIn('id_penyakit', [$this->request->getVar('id_penyakit')])
            ->set([
                // 'penyakit' => $this->request->getVar('penyakit'),
                'obat' => $this->request->getVar('obat'),
                'solusi' => $this->request->getVar('solusi'),
            ])
            ->update();

        session()->setFlashdata('pesan', 'Penyakit berhasil diubah!');
        return redirect()->to('/admin/penyakit');
    }

    public function deletePenyakit()
    {
        $this->penyakitModel->where('id_penyakit', [$this->request->getVar('id_penyakit')])->delete();

        session()->setFlashdata('pesan', 'Penyakit berhasil dihapus');
        return redirect()->to('/admin/penyakit');
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
        ];
        $data['gejala'] = $this->gejalaModel->findall();
        $data['penyakit'] = $this->penyakitModel->findall();

        return view('admin/sample', $data);
    }

    public function tambahSample()
    {
        //get data
        $gejala = $this->request->getVar('sampleGejala');
        $penyakit = $this->request->getVar('samplePenyakit');


        $this->sampleModel->save([
            'id_gejala' => $gejala,
            'id_penyakit' => $penyakit,
        ]);

        session()->setFlashdata('pesanSample', 'Sample berhasil ditambahkan, harap click tombol Start Mining untuk proses data mining!');
        return redirect()->to('/admin/datasample');
    }

    public function updateSample()
    {
        //query update
        $this->sampleModel
            ->whereIn('id_sample', [$this->request->getVar('id_sample')])
            ->set([
                'id_gejala' => $this->request->getVar('sampleGejala'),
                'id_penyakit' => $this->request->getVar('samplePenyakit'),
            ])
            ->update();

        session()->setFlashdata('pesanSample', 'Data sample berhasil diubah, harap click tombol Start Mining untuk proses data mining!');
        return redirect()->to('/admin/datasample');
    }

    public function deleteSample()
    {
        $this->sampleModel->where('id_sample', [$this->request->getVar('id_sample')])->delete();

        session()->setFlashdata('pesanSample', 'Sample berhasil dihapus, harap click tombol Start Mining untuk proses data mining!');

        return redirect()->to('/admin/datasample');
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
        $currentPage = $this->request->getVar('decision_tree') ? $this->request->getVar('decision_tree') : 1;

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $decisiontree = $this->decisionTreeModel->search($keyword);
        } else {
            $decisiontree = $this->decisionTreeModel;
        }

        $page = 8;
        // $data = [
        //     'title' => 'Decision Tree',
        //     'decisiontree' => $decisiontree->paginate($page, 'decision_tree'),
        //     'pager' => $this->decisionTreeModel->pager,
        //     'currentPage' => $currentPage,
        //     'page' => $page,
        // ];

        $data = [
            'title' => 'Decision Tree',
            'decisiontree' => $decisiontree->select('decision_tree.parent, decision_tree.akar, decision_tree.keputusan, data_penyakit.penyakit')
                ->join('data_penyakit', 'data_penyakit.id_penyakit = decision_tree.keputusan')->paginate($page, 'decision_tree'),
            'pager' => $this->decisionTreeModel->pager,
            'currentPage' => $currentPage,
            'page' => $page,
        ];
        $data['penyakit'] = $this->penyakitModel->findall();

        return view('admin/decisiontree', $data);
    }
}
