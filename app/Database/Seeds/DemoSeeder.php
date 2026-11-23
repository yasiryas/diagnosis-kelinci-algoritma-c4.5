<?php

namespace App\Database\Seeds;

use App\Services\PasswordHasher;
use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $this->seedAuthGroups();
        $this->seedUsers();
        $this->seedGejala();
        $this->seedPenyakit();
        $this->seedSample();
        $this->seedDecisionTree();
        $this->seedHasil();
        $this->seedArticles();
    }

    private function seedAuthGroups(): void
    {
        foreach (['admin' => 'Administrator', 'user' => 'Regular user'] as $name => $description) {
            $this->upsert('auth_groups', ['name' => $name], ['description' => $description]);
        }

        foreach (['manage-user' => 'Manage All User', 'manage-profile' => "Manage user's profile"] as $name => $description) {
            $this->upsert('auth_permissions', ['name' => $name], ['description' => $description]);
        }

        $grants = [
            'admin' => ['manage-user', 'manage-profile'],
            'user' => ['manage-profile'],
        ];

        foreach ($grants as $groupName => $permissions) {
            $group = $this->db->table('auth_groups')->where('name', $groupName)->get()->getFirstRow();
            if ($group === null) {
                continue;
            }

            foreach ($permissions as $permissionName) {
                $permission = $this->db->table('auth_permissions')->where('name', $permissionName)->get()->getFirstRow();
                if ($permission === null) {
                    continue;
                }

                $this->upsert('auth_groups_permissions', [
                    'group_id' => (int) $group->id,
                    'permission_id' => (int) $permission->id,
                ], []);
            }
        }
    }

    private function seedUsers(): void
    {
        $passwordHash = PasswordHasher::hash('123');
        $users = [
            [
                'email' => 'admin@mail.com',
                'username' => 'admin',
                'fullname' => 'Administrator',
                'group' => 'admin',
            ],
            [
                'email' => 'user1@mail.com',
                'username' => 'user1',
                'fullname' => 'User Satu',
                'group' => 'user',
            ],
            [
                'email' => 'user2@mail.com',
                'username' => 'user2',
                'fullname' => 'User Dua',
                'group' => 'user',
            ],
        ];

        foreach ($users as $user) {
            $this->upsert('users', ['email' => $user['email']], [
                'username' => $user['username'],
                'fullname' => $user['fullname'],
                'password_hash' => $passwordHash,
                'active' => 1,
                'status' => 1,
                'status_message' => null,
                'force_pass_reset' => 0,
                'user_image' => 'default.svg',
            ]);

            $seededUser = $this->db->table('users')->where('email', $user['email'])->get()->getFirstRow();
            $group = $this->db->table('auth_groups')->where('name', $user['group'])->get()->getFirstRow();
            if ($seededUser === null || $group === null) {
                continue;
            }

            $this->upsert('auth_groups_users', [
                'user_id' => (int) $seededUser->id,
                'group_id' => (int) $group->id,
            ], []);
        }
    }

    private function seedGejala(): void
    {
        $rows = [
            [19, 'Kulit ', 'Membengkak'],
            [20, 'Kotoran ', 'Sedikit'],
            [21, 'Kotoran', 'Encer'],
            [22, 'Kotoran', 'Normal'],
            [23, 'Perut', 'Kosong'],
            [24, 'Perut', 'Berisi'],
        ];

        foreach ($rows as [$id, $kategori, $gejala]) {
            $this->upsert('data_gejala', ['id_gejala' => $id], [
                'kategori' => $kategori,
                'gejala' => $gejala,
            ]);
        }
    }

    private function seedPenyakit(): void
    {
        $rows = [
            [4, 'Sembelit', 'Rebung', 'Tambah makanan berserat'],
            [5, 'Diare', 'Rebung', 'Dikasih makanan yang bernutrisi'],
            [6, 'Kembung', 'Rebung', 'Makanan yang diberikan harus kering'],
            [7, 'Scabies', 'Wormmectin', 'Disuntik dengan obat dan diberi salep'],
        ];

        foreach ($rows as [$id, $penyakit, $obat, $solusi]) {
            $this->upsert('data_penyakit', ['id_penyakit' => $id], [
                'penyakit' => $penyakit,
                'obat' => $obat,
                'solusi' => $solusi,
            ]);
        }
    }

    private function seedSample(): void
    {
        $rows = [
            [8, 19, 7], [9, 22, 7], [10, 24, 7], [12, 20, 4], [15, 21, 5],
            [16, 23, 5], [21, 20, 4], [22, 24, 4], [23, 19, 7], [24, 22, 7],
            [25, 24, 7], [27, 21, 5], [28, 24, 5], [30, 22, 6], [31, 23, 6],
            [33, 21, 5], [34, 24, 5], [36, 20, 4], [37, 24, 4], [39, 22, 6],
            [40, 23, 6], [44, 19, 7], [45, 22, 7], [46, 24, 7], [48, 20, 4],
            [49, 24, 4], [51, 21, 5], [52, 23, 5], [54, 22, 6], [55, 23, 6],
            [57, 20, 4], [58, 24, 4], [63, 22, 6], [64, 23, 6], [66, 21, 5],
            [67, 23, 5], [68, 24, 4],
        ];

        foreach ($rows as [$id, $idGejala, $idPenyakit]) {
            $this->upsert('data_sample', ['id_sample' => $id], [
                'id_gejala' => $idGejala,
                'id_penyakit' => $idPenyakit,
            ]);
        }
    }

    private function seedDecisionTree(): void
    {
        $rows = [
            [1, '20', 'Kotoran  Sedikit', 'Kotoran ', 4],
            [2, '21', 'Kotoran Encer', 'Kotoran', 5],
            [3, '22 19', 'Kotoran Normal dan Kulit  Membengkak', 'Kulit ', 7],
            [4, '22 23', 'Kotoran Normal dan Perut Kosong', 'Perut', 6],
        ];

        foreach ($rows as [$id, $parent, $detail, $akar, $keputusan]) {
            $this->upsert('decision_tree', ['id' => $id], [
                'parent' => $parent,
                'detail' => $detail,
                'akar' => $akar,
                'keputusan' => $keputusan,
            ]);
        }
    }

    private function seedHasil(): void
    {
        $userSatu = $this->db->table('users')->where('email', 'user1@mail.com')->get()->getFirstRow();
        $userDua = $this->db->table('users')->where('email', 'user2@mail.com')->get()->getFirstRow();
        $idSatu = $userSatu === null ? 1 : (int) $userSatu->id;
        $idDua = $userDua === null ? 2 : (int) $userDua->id;

        $rows = [
            [1, $idSatu, 4, 'Sembelit'],
            [2, $idSatu, 4, 'Sembelit'],
            [3, $idSatu, 5, 'Diare'],
            [4, $idSatu, 4, 'Sembelit'],
            [5, $idDua, 4, 'Sembelit'],
        ];

        foreach ($rows as [$id, $idUser, $idPenyakit, $penyakit]) {
            $this->upsert('hasil', ['id_hasil' => $id], [
                'id_user' => $idUser,
                'id_penyakit' => $idPenyakit,
                'penyakit' => $penyakit,
            ]);
        }
    }

    private function seedArticles(): void
    {
        $categories = [
            ['perawatan', 'Perawatan'],
            ['penyakit', 'Penyakit'],
            ['nutrisi', 'Nutrisi'],
            ['pencegahan', 'Pencegahan'],
        ];

        foreach ($categories as [$slug, $nama]) {
            $this->upsert('kategori_artikel', ['slug' => $slug], [
                'nama' => $nama,
                'created_at' => '2026-11-20 00:00:00',
                'updated_at' => '2026-11-20 00:00:00',
            ]);
        }

        $articles = [
            [
                'slug' => 'cara-merawat-kelinci-sembelit',
                'judul' => 'Cara Merawat Kelinci Sembelit',
                'ringkasan' => 'Kenali tanda sembelit dan penanganan awal di rumah.',
                'konten' => 'Sembelit ditandai dengan kotoran yang sedikit dan sulit keluar. Tambahkan serat segar, air bersih, dan ajak kelinci bergerak setiap hari. Jika gejala berlanjut, segera konsultasikan ke dokter hewan.',
                'status' => 'publish',
                'published_at' => '2026-11-20 08:00:00',
                'kategori' => 'perawatan',
            ],
            [
                'slug' => 'mengenali-diare-pada-kelinci',
                'judul' => 'Mengenali Diare pada Kelinci',
                'ringkasan' => 'Perbedaan kotoran encer normal dan diare yang berbahaya.',
                'konten' => 'Kotoran encer yang disertai lemas, nafsu makan turun, atau dehidrasi perlu penanganan cepat. Berikan pakan bernutrisi dan hindari perubahan menu mendadak.',
                'status' => 'publish',
                'published_at' => '2026-11-19 08:00:00',
                'kategori' => 'penyakit',
            ],
            [
                'slug' => 'nutrisi-seimbang-untuk-kelinci',
                'judul' => 'Nutrisi Seimbang untuk Kelinci',
                'ringkasan' => 'Komposisi pakan harian yang mendukung pencernaan kelinci.',
                'konten' => 'Utamakan rumput segar, air bersih, dan sayur dalam porsi terukur. Batasi konsentrat dan pastikan kandang tetap bersih agar nafsu makan stabil.',
                'status' => 'publish',
                'published_at' => '2026-11-18 08:00:00',
                'kategori' => 'nutrisi',
            ],
            [
                'slug' => 'pencegahan-scabies-kelinci',
                'judul' => 'Pencegahan Scabies Kelinci',
                'ringkasan' => 'Langkah karantina dan kebersihan kandang untuk mencegah scabies.',
                'konten' => 'Artikel ini menjelaskan pemeriksaan kulit, karantina kelinci baru, dan sanitasi kandang. Materi masih dalam penyusunan sehingga belum ditampilkan di halaman publik.',
                'status' => 'draft',
                'published_at' => null,
                'kategori' => 'pencegahan',
            ],
        ];

        foreach ($articles as $article) {
            $this->upsert('artikel', ['slug' => $article['slug']], [
                'judul' => $article['judul'],
                'ringkasan' => $article['ringkasan'],
                'konten' => $article['konten'],
                'status' => $article['status'],
                'published_at' => $article['published_at'],
                'created_at' => '2026-11-20 00:00:00',
                'updated_at' => '2026-11-20 00:00:00',
            ]);

            $seededArticle = $this->db->table('artikel')->where('slug', $article['slug'])->get()->getFirstRow();
            $category = $this->db->table('kategori_artikel')->where('slug', $article['kategori'])->get()->getFirstRow();
            if ($seededArticle === null || $category === null) {
                continue;
            }

            $this->upsert('artikel_kategori', [
                'artikel_id' => (int) $seededArticle->id,
                'kategori_id' => (int) $category->id,
            ], []);
        }
    }

    private function upsert(string $table, array $naturalKey, array $data): void
    {
        $builder = $this->db->table($table);
        foreach ($naturalKey as $column => $value) {
            $builder->where($column, $value);
        }
        $existing = $builder->get()->getFirstRow();

        if ($existing === null) {
            $this->db->table($table)->insert(array_merge($naturalKey, $data));
            return;
        }

        if ($data === []) {
            return;
        }

        $builder = $this->db->table($table);
        foreach ($naturalKey as $column => $value) {
            $builder->where($column, $value);
        }
        $builder->update($data);
    }
}
