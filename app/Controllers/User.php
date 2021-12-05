<?php

namespace App\Controllers;

use Myth\Auth\Models\UserModel;
use App\Models\gejalaModel;
use App\Models\decisionTreeModel;
use App\Models\penyakitModel;
use App\Models\listModel;


class User extends BaseController
{
    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;
    protected $db, $builder;

    public function __construct()
    {
        $this->db      = \Config\Database::connect();
        $this->builder = $this->db->table('users');
        $this->gejalaModel = new gejalaModel();
        $this->decisionTreeModel = new decisionTreeModel();
        $this->penyakitModel = new penyakitModel();
        $this->userModel = new UserModel();
        $this->listModel = new listModel();
    }

    public function index()
    {
        //Jumlah User Terdaftar
        $countUser = $this->userModel->countUser();

        //Total gejala yang terdaftar
        $countGejala = $this->gejalaModel->countGejala();

        //Total penyakit yang terdaftar
        $countPenyakit = $this->penyakitModel->countPenyakit();

        //Jumlah record konsultasi (Admin: All - User: idUser)
        $id_user = user()->id;
        $user = $this->listModel->checkUser($id_user);
        if ($user->name == "admin") {
            $countAllHasil = $this->listModel->countAllHasil();
        } else {
            $countAllHasil = $this->listModel->countHasil($id_user);
        }

        $data = [
            'title' => 'Dashboard',
            'user' => $countUser,
            'gejala' => $countGejala,
            'penyakit' => $countPenyakit,
            'hasil' => $countAllHasil,
        ];
        return view('user/index', $data);
    }

    public function diagnosis()
    {
        $data['title'] = 'Diagnosis';
        return view('user/diagnosis', $data);
    }

    public function profile()
    {
        $data['title'] = 'My Profile';

        $this->builder->select('users.id as userid, username, email, name, fullname, user_image');
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $this->builder->where('users.id', user()->id);
        $query = $this->builder->get();

        $data['user'] = $query->getRow();

        return view('user/profile', $data);
    }

    public function edit()
    {
        $data = [
            'title' => 'Edit Profile',
            'validation' => \Config\Services::validation(),
        ];

        return view('/user/edit', $data);
    }

    public function changePassword()
    {
        $data['title'] = 'Change Password';
        return view('user/changepassword', $data);
    }

    public function updateProfile($id)
    {
        //validasi input
        if (!$this->validate([
            'username' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi'
                ]
            ],
            'fullname' => [
                'rules' => 'alpha_space',
                'errors' => [
                    'required' => '{field} harus berisi huruf tidak boleh berisi karakter lain'
                ]
            ],
            'user_image' => [
                'rules' => 'max_size[user_image,1024]|is_image[user_image]|mime_in[user_image,image/jpg,image/jpeg,image/png,image/svg]|max_dims[user_image,1080,1080]',
                'errors' => [
                    'max_size' => 'Ukuran gambar maksimum 1 MB',
                    'is_image' => 'File bukan gambar',
                    'mime_in' => 'File bukan gambar',
                    'max_dims' => 'Ukuran gambar harus kurang dari 1080px'
                ]
            ]
        ])) {
            return redirect()->to('/user/edit/')->withInput();
        }

        $fileImageProfile = $this->request->getFile('user_image');

        //check image, old image
        if ($fileImageProfile->getError() == 4) {
            $nameImage = $this->request->getVar('userImageOld');
        } else {
            //genrate name image random
            $nameImage = $fileImageProfile->getRandomName();
            // move image
            $fileImageProfile->move('img/profile/', $nameImage);
            //delete file old
            $userImageOld = $this->request->getVar('userImageOld');
            if (!$userImageOld = 'default.svg') {
                unlink('img/profile/' . $userImageOld);
            }
        }

        $userModel = model(UserModel::class);

        $userModel->save([
            'id' => $id,
            'username' => $this->request->getVar('username'),
            'fullname' => $this->request->getVar('fullname'),
            'user_image' => $nameImage
        ]);

        session()->setFlashdata('pesan', 'Data berhasil diubah');

        return redirect()->to('/user/profile');
    }

    public function updatePassword()
    {
        //validasi input
        if (!$this->validate([
            'oldpassword' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi'
                ]
            ],
            'newpassword' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi'
                ]
            ],
            'repeatnewpassword' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi'
                ]
            ]
        ])) {
            return redirect()->to('/user/changepassword/')->withInput();
        }
    }

    public function prosesDiagnosis()
    {
        $gejala = $this->gejalaModel->orderBy('kategori')->findAll();

        $data = [
            'title' => 'Diagnosis',
            'gejala' => $gejala,
        ];

        return view('/user/prosesdiagnosis', $data);
    }

    public function konsultasi()
    {
        $countTree = $this->decisionTreeModel->countTree();
        $count = 0;
        $gejala = $this->request->getVar('gejala');
        if ($gejala == null) {
            session()->setFlashdata('diagnosis', 'Harus memilih gejala minimal 1');
            return redirect()->to('/user/prosesdiagnosis');
        }

        $rule = $this->decisionTreeModel->findAll();
        foreach ($rule as $r) :
            $parent = $r['parent'];
            $ruleSet = explode(" ", $parent);

            if ($gejala == $ruleSet) {
                $detail = $r['detail'];
                $penyakit = $r['keputusan'];
                $id = (int) $penyakit;
                $id_user = user()->id;
                $hasil = $this->penyakitModel->hasil($id);
                $penyakit = $hasil->penyakit;
                $this->decisionTreeModel->saveResult($id_user, $id, $penyakit);
            } else {
                $count++;
            }
        endforeach;

        // count gejala
        if ($countTree == $count) {
            session()->setFlashdata('diagnosis', 'Gejala masih belum sesuai, harap pilih gejala yang sesuai!');
            return redirect()->to('/user/prosesdiagnosis');
        }


        // $hasil = $this->penyakitModel->hasil($id);
        $data = [
            'title' => 'Diagnosis',
            // 'hasil' => $this->penyakitModel->hasil($id),
            'hasil' => $hasil,
            'gejala' => $detail,
        ];

        return view('/user/hasildiagnosis', $data);
    }
}
