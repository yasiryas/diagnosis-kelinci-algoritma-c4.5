<?php

namespace App\Database\Seeds;

use App\Services\PasswordHasher;
use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $this->seedAuthGroups();
        $this->seedUsers();
    }

    private function seedAuthGroups()
    {
        foreach (['admin', 'user'] as $groupName) {
            $exists = $this->db->table('auth_groups')
                ->where('name', $groupName)
                ->get()
                ->getFirstRow();

            if (!$exists) {
                $this->db->table('auth_groups')->insert([
                    'name'        => $groupName,
                    'description' => $groupName == 'admin' ? 'Administrator' : 'Regular user',
                ]);
            }
        }
    }

    private function seedUsers()
    {
        $adminGroup = $this->db->table('auth_groups')->where('name', 'admin')->get()->getFirstRow();
        $userGroup = $this->db->table('auth_groups')->where('name', 'user')->get()->getFirstRow();

        $passwordHash = PasswordHasher::hash('123');

        $users = [
            [
                'email'       => 'admin@mail.com',
                'username'    => 'admin',
                'fullname'    => 'Administrator',
                'password_hash' => $passwordHash,
                'active'      => 1,
                'status'      => 1,
                'status_message' => null,
                'force_pass_reset' => 0,
                'user_image' => 'default.svg',
            ],
            [
                'email'       => 'user1@mail.com',
                'username'    => 'user1',
                'fullname'    => 'User Satu',
                'password_hash' => $passwordHash,
                'active'      => 1,
                'status'      => 1,
                'status_message' => null,
                'force_pass_reset' => 0,
                'user_image' => 'default.svg',
            ],
            [
                'email'       => 'user2@mail.com',
                'username'    => 'user2',
                'fullname'    => 'User Dua',
                'password_hash' => $passwordHash,
                'active'      => 1,
                'status'      => 1,
                'status_message' => null,
                'force_pass_reset' => 0,
                'user_image' => 'default.svg',
            ],
        ];

        foreach ($users as $user) {
            $existing = $this->db->table('users')
                ->where('email', $user['email'])
                ->get()
                ->getFirstRow();

            if (!$existing) {
                $this->db->table('users')->insert($user);
            }
        }

        foreach ($users as $user) {
            $seededUser = $this->db->table('users')->where('email', $user['email'])->get()->getFirstRow();

            if ($seededUser === null) {
                continue;
            }

            $group = $user['email'] === 'admin@mail.com' ? $adminGroup : $userGroup;
            $exists = $this->db->table('auth_groups_users')
                ->where('user_id', $seededUser->id)
                ->where('group_id', $group->id)
                ->get()
                ->getFirstRow();

            if ($exists === null) {
                $this->db->table('auth_groups_users')->insert([
                    'user_id'  => (int) $seededUser->id,
                    'group_id' => (int) $group->id,
                ]);
            }
        }
    }
}
