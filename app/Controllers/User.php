<?php

namespace App\Controllers;

use Myth\Auth\Models\UserModel;

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
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
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
}
