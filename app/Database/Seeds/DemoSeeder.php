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
                'gambar' => 'https://images.unsplash.com/photo-1587599774319-f4866eaafecc?w=600&h=400&fit=crop',
            ],
            [
                'slug' => 'mengenali-diare-pada-kelinci',
                'judul' => 'Mengenali Diare pada Kelinci',
                'ringkasan' => 'Perbedaan kotoran encer normal dan diare yang berbahaya.',
                'konten' => 'Kotoran encer yang disertai lemas, nafsu makan turun, atau dehidrasi perlu penanganan cepat. Berikan pakan bernutrisi dan hindari perubahan menu mendadak.',
                'status' => 'publish',
                'published_at' => '2026-11-19 08:00:00',
                'kategori' => 'penyakit',
                'gambar' => 'https://images.unsplash.com/photo-1581973811574-1a99a9a01b18?w=600&h=400&fit=crop',
            ],
            [
                'slug' => 'nutrisi-seimbang-untuk-kelinci',
                'judul' => 'Nutrisi Seimbang untuk Kelinci',
                'ringkasan' => 'Komposisi pakan harian yang mendukung pencernaan kelinci.',
                'konten' => 'Utamakan rumput segar, air bersih, dan sayur dalam porsi terukur. Batasi konsentrat dan pastikan kandang tetap bersih agar nafsu makan stabil.',
                'status' => 'publish',
                'published_at' => '2026-11-18 08:00:00',
                'kategori' => 'nutrisi',
                'gambar' => 'https://images.unsplash.com/photo-1615742005283-0666d4a09c14?w=600&h=400&fit=crop',
            ],
            [
                'slug' => 'pencegahan-scabies-kelinci',
                'judul' => 'Pencegahan Scabies Kelinci',
                'ringkasan' => 'Langkah karantina dan kebersihan kandang untuk mencegah scabies.',
                'konten' => 'Artikel ini menjelaskan pemeriksaan kulit, karantina kelinci baru, dan sanitasi kandang. Materi masih dalam penyusunan sehingga belum ditampilkan di halaman publik.',
                'status' => 'draft',
                'published_at' => null,
                'kategori' => 'pencegahan',
                'gambar' => 'https://images.unsplash.com/photo-1606151410160-5b8e5a10b89b?w=600&h=400&fit=crop',
            ],
            [
                'slug' => 'ciri-ciri-kelinci-sehat',
                'judul' => 'Ciri-ciri Kelinci Sehat',
                'ringkasan' => 'Tanda-tanda kelinci yang sehat dari perilaku hingga kondisi tubuhnya.',
                'konten' => 'Kelinci sehat biasanya aktif, selera makan baik, mata bersih dan cemerlang, serta kotoran berukuran seragam. Perhatikan perubahan perilaku sebagai tanda awal masalah kesehatan.',
                'status' => 'publish',
                'published_at' => '2026-11-17 08:00:00',
                'kategori' => 'perawatan',
                'gambar' => 'https://images.unsplash.com/photo-1577534273747-2104411e8e87?w=600&h=400&fit=crop',
            ],
            [
                'slug' => 'cara-pilih-pakan-kelinci',
                'judul' => 'Cara Memilih Pakan yang Tepat untuk Kelinci',
                'ringkasan' => 'Panduan memilih pakan berkualitas untuk pertumbuhan kelinci yang optimal.',
                'konten' => 'Pilihan pakan harus sesuai usia dan kondisi kelinci. Hindari pakan yang mudah basmi atau berbau busuk. Berikan rumput segar sebagai pakan utama dan konsentrat sebagai tambahan.',
                'status' => 'publish',
                'published_at' => '2026-11-16 08:00:00',
                'kategori' => 'nutrisi',
                'gambar' => 'https://images.unsplash.com/photo-1623411985083-dc881e5feb8f?w=600&h=400&fit=crop',
            ],
            [
                'slug' => 'mengenal-coccidiosis-pada-kelinci',
                'judul' => 'Mengenal Coccidiosis pada Kelinci',
                'ringkasan' => 'Penyakit parasit pada saluran pencernaan kelinci yang perlu penanganan segera.',
                'konten' => 'Coccidiosis disebabkan oleh parasit Eimeria. Gejalanya antara lain diare berdarah, penurunan berat badan, dan kelinci terlihat lemah. Segera konsultasikan ke dokter hewan untuk pengobatan.',
                'status' => 'publish',
                'published_at' => '2026-11-15 08:00:00',
                'kategori' => 'penyakit',
                'gambar' => 'https://images.unsplash.com/photo-1576199838305-6cc8eb3f9cf8?w=600&h=400&fit=crop',
            ],
            [
                'slug' => 'menjaga-kebersihan-kandang-kelinci',
                'judul' => 'Menjaga Kebersihan Kandang Kelinci',
                'ringkasan' => 'Rutin membersihkan kandang untuk mencegah penyakit dan parasit.',
                'konten' => 'Kandang yang kotor menjadi tempat berkembang biaknya kuman dan parasit. Bersihkan kandang minimal satu hari sekali, ganti bersi secara teratur, dan pastikan drainase air baik.',
                'status' => 'publish',
                'published_at' => '2026-11-14 08:00:00',
                'kategori' => 'pencegahan',
                'gambar' => 'https://images.unsplash.com/photo-1597564795408-7df02c378b35?w=600&h=400&fit=crop',
            ],
            [
                'slug' => 'vaksinasi-kelinci-apa-yang-perlu-diketahui',
                'judul' => 'Vaksinasi Kelinci: Apa yang Perlu Diketahui',
                'ringkasan' => 'Jadwal dan jenis vaksinasi untuk melindungi kelinci dari penyakit berbahaya.',
                'konten' => 'Vaksinasi membantu melindungi kelinci dari berbagai penyakit. Konsultasikan dengan dokter hewan untuk menentukan jadwal vaksinasi yang sesuai dengan usia dan kondisi kelinci Anda.',
                'status' => 'publish',
                'published_at' => '2026-11-13 08:00:00',
                'kategori' => 'pencegahan',
                'gambar' => 'https://images.unsplash.com/photo-1589711014172-5b3ad81e5e28?w=600&h=400&fit=crop',
            ],
            [
                'slug' => 'cara-pijat-kelinci-nyaman',
                'judul' => 'Cara Pijat Kelinci agar Nyaman',
                'ringkasan' => 'Teknik sederhana untuk menjaga kelinci tetap rileks dan nyaman.',
                'konten' => 'Pijat perlahan di sekitar telinga dan punggung kelinci. Hindari area yang sensitif dan perhatikan reaksi kelinci saat dipijat. Ini juga membantu mengikat tali talak antara pemilik dan hewan peliharaan.',
                'status' => 'publish',
                'published_at' => '2026-11-12 08:00:00',
                'kategori' => 'perawatan',
                'gambar' => 'https://images.unsplash.com/photo-1552053832-883785af4801?w=600&h=400&fit=crop',
            ],
            [
                'slug' => 'gejala-kelinci-muntah-dan-solusinya',
                'judul' => 'Apakah Kelinci Bisa Muntah?',
                'ringkasan' => 'Kelinci tidak dapat muntah. Kenali tanda yang perlu segera diperiksa dokter hewan.',
                'konten' => 'Kelinci tidak dapat muntah. Gerakan seperti tersedak, air liur berlebihan, atau kesulitan bernapas perlu segera diperiksa. Jika kelinci berhenti makan atau tidak mengeluarkan kotoran, segera hubungi dokter hewan dan jangan menunggu 24 jam. Jangan memberikan obat tanpa arahan dokter hewan.',
                'status' => 'publish',
                'published_at' => '2026-11-11 08:00:00',
                'kategori' => 'penyakit',
                'gambar' => 'https://images.unsplash.com/photo-1601026274451-779660acbad5?w=600&h=400&fit=crop',
            ],
            [
                'slug' => 'mengenal-gejala-berat-badan-kelinci-turun',
                'judul' => 'Mengenal Gejala Berat Badan Kelinci Turun',
                'ringkasan' => 'Tanda-tanda berat badan kelinci menurun dan penyebabnya.',
                'konten' => 'Penurunan berat badan yang tiba-tiba bisa disebabkan oleh parasit dalam, masalah pencernaan, atau stres. Timbang kelinci secara rutin dan konsultasikan ke dokter hewan jika ada penurunan signifikan.',
                'status' => 'publish',
                'published_at' => '2026-11-10 08:00:00',
                'kategori' => 'penyakit',
                'gambar' => 'https://images.unsplash.com/photo-1589008329387-1425913a5254?w=600&h=400&fit=crop',
            ],
            [
                'slug' => 'tips-menjaga-kesehatan-gigi-kelinci',
                'judul' => 'Tips Menjaga Kesehatan Gigi Kelinci',
                'ringkasan' => 'Gigi kelinci terus tumbuh, perawatan gigi sangat penting.',
                'konten' => 'Gigi kelinci terus tumbuh seumur hidup. Berikan kayu kawat atau gandum utuh agar gigi tergores secara alami. Periksa gigi secara teratur dan hindari pakan yang terlalu lunak.',
                'status' => 'publish',
                'published_at' => '2026-11-09 08:00:00',
                'kategori' => 'perawatan',
                'gambar' => 'https://images.unsplash.com/photo-1563960055-092c9e9d8e1e?w=600&h=400&fit=crop',
            ],
            [
                'slug' => 'mengenal-parasit-dalam-pada-kelinci',
                'judul' => 'Mengenal Parasit Dalam pada Kelinci',
                'ringkasan' => 'Cara mendeteksi dan mengobati parasit dalam pada saluran pencernaan kelinci.',
                'konten' => 'Parasit dalam seperti cacing dapat menyebabkan berat badan turun dan diare. Berikan obat cacing sesuai rekomendasi dokter hewan dan jaga kebersihan kandang untuk mencegah infeksi ulang.',
                'status' => 'draft',
                'published_at' => null,
                'kategori' => 'penyakit',
                'gambar' => 'https://images.unsplash.com/photo-1591445244751-8289e52e9a8c?w=600&h=400&fit=crop',
            ],
        ];

        foreach ($articles as $article) {
            $this->upsert('artikel', ['slug' => $article['slug']], [
                'judul' => $article['judul'],
                'ringkasan' => $article['ringkasan'],
                'konten' => $article['konten'],
                'status' => $article['status'],
                'published_at' => $article['published_at'],
                'gambar' => $article['gambar'],
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
