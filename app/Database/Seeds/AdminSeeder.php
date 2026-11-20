<?php

namespace App\Database\Seeds;

use App\Services\PasswordHasher;
use CodeIgniter\Database\Seeder;
use Myth\Auth\Models\UserModel;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();
        $existing = $userModel->where('email', 'admin@mail.com')->first();

        if ($existing !== null) {
            return;
        }

        $userModel->withGroup('admin')->insert([
            'email'         => 'admin@mail.com',
            'username'      => 'admin',
            'fullname'      => 'Administrator',
            'password_hash' => PasswordHasher::hash('123'),
            'active'        => 1,
            'user_image'    => 'default.svg',
        ]);
    }
}
