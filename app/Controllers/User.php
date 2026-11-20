<?php

namespace App\Controllers;

use Myth\Auth\Models\UserModel;
use App\Models\gejalaModel;
use App\Models\decisionTreeModel;
use App\Models\penyakitModel;
use App\Models\listModel;
use App\Services\DiagnosisRuleMatcher;
use App\Services\PasswordChangeValidator;


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

    public function updateProfile()
    {
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
                    'alpha_space' => '{field} harus berisi huruf tidak boleh berisi karakter lain'
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
            return redirect()->to('/user/edit')->withInput();
        }

        $currentUser = $this->userModel->find((int) user()->id);
        $oldImage = $currentUser === null ? 'default.svg' : (string) $currentUser->user_image;
        $fileImageProfile = $this->request->getFile('user_image');
        $nameImage = $oldImage;

        if ($fileImageProfile !== null && $fileImageProfile->getError() !== 4) {
            $nameImage = $fileImageProfile->getRandomName();
            $fileImageProfile->move(FCPATH . 'img/profile', $nameImage);
            $this->deleteProfileImage($oldImage);
        }

        $this->userModel->save([
            'id' => (int) user()->id,
            'username' => $this->request->getPost('username'),
            'fullname' => $this->request->getPost('fullname'),
            'user_image' => $nameImage,
        ]);

        session()->setFlashdata('pesan', 'Data berhasil diubah');

        return redirect()->to('/user/profile');
    }

    private function deleteProfileImage(string $filename): void
    {
        if ($filename === '' || $filename === 'default.svg') {
            return;
        }

        $path = FCPATH . 'img/profile/' . basename($filename);

        if (is_file($path)) {
            unlink($path);
        }
    }

    public function updatePassword()
    {
        $user = $this->userModel->find((int) user()->id);

        if ($user === null) {
            session()->setFlashdata('passwordErrors', ['current' => 'Pengguna tidak ditemukan']);
            return redirect()->to('/user/changepassword');
        }

        $validator = new PasswordChangeValidator();
        $errors = $validator->validate(
            (string) $user->password_hash,
            (string) $this->request->getPost('oldpassword'),
            (string) $this->request->getPost('newpassword'),
            (string) $this->request->getPost('repeatnewpassword')
        );

        if ($errors !== []) {
            session()->setFlashdata('passwordErrors', $errors);
            return redirect()->to('/user/changepassword')->withInput();
        }

        $user->password = $this->request->getPost('newpassword');
        $this->userModel->save($user);

        session()->setFlashdata('pesan', 'Password berhasil diubah');

        return redirect()->to('/user/profile');
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
        $gejala = $this->request->getPost('gejala');

        if (empty($gejala)) {
            session()->setFlashdata('diagnosis', 'Harus memilih gejala minimal 1');
            return redirect()->to('/user/prosesdiagnosis');
        }

        $selected = is_array($gejala) ? $gejala : [$gejala];
        $matcher = new DiagnosisRuleMatcher();
        $rule = $matcher->match($selected, $this->decisionTreeModel->findAll());

        if ($rule === null) {
            session()->setFlashdata('diagnosis', 'Gejala masih belum sesuai, harap pilih gejala yang sesuai!');
            return redirect()->to('/user/prosesdiagnosis');
        }

        $idPenyakit = (int) $rule['keputusan'];
        $penyakit = $this->penyakitModel->hasil($idPenyakit);

        if ($penyakit === null) {
            session()->setFlashdata('diagnosis', 'Penyakit untuk rule ini tidak ditemukan');
            return redirect()->to('/user/prosesdiagnosis');
        }

        $this->decisionTreeModel->saveResult((int) user()->id, $idPenyakit, $penyakit->penyakit);

        $data = [
            'title' => 'Diagnosis',
            'hasil' => $penyakit,
            'gejala' => $rule['detail'] ?? '',
        ];

        return view('/user/hasildiagnosis', $data);
    }
}
