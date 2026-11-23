# Diagnosis Kelinci — perluasan CRUD, artikel, chart, pohon, dan diagnosis tamu

## Intent

Aplikasi sudah memakai SB Admin 2, tetapi CRUD masih POST biasa dan reload penuh, dashboard hanya kartu angka, pohon keputusan masih tabel, belum ada manajemen artikel/data contoh lengkap, dan diagnosis hanya bisa setelah login. Perubahan ini membuat alur admin cepat tanpa reload, menambahkan konten artikel, visualisasi yang sesuai SB Admin, pohon keputusan nyata, serta konsultasi tamu yang tetap aman.

## Goals

- Semua CRUD admin dan update profil/password berjalan lewat modal + AJAX POST JSON, dengan CSRF, validasi inline, dan umpan balik loading/success/error.
- Dashboard menampilkan chart ringkas dari data yang sudah ada tanpa menggantikan angka sebagai teks.
- Manajemen artikel lengkap: migrasi, model, CRUD modal AJAX, rute admin, menu, halaman baca publik, dan seeder.
- `decision_tree` dirender sebagai pohon node-edge svg, bukan tabel; tabel hanya fallback aksesibel.
- Tamu bisa mencoba diagnosis tanpa akun, tanpa menulis ke tabel `hasil`.
- Data seeder lengkap dan idempoten untuk demo.
- Query, font, CSS, dan aset JS inconsistencies dibersihkan.

## Non-goals

- Mengganti SB Admin 2 dengan template lain.
- Menambah dependency npm/composer baru; Chart.js 2.9.4 yang sudah ada cukup.
- Membuat sistem antrean worker. AJAX POST biasa + JSON + CSRF tanpa reload sudah dipilih.
- Menyalin data historis `auth_logins`/`auth_reset_attempts` dari dump lama.

## Constraints

- CodeIgniter 4 + Myth Auth, PHP, MySQL/MariaDB.
- SB Admin 2 v4.1.3 + Bootstrap 4.5.3 + Nunito + Chart.js 2.9.4 sudah tersedia di `public/`.
- CSRF global aktif dengan `regenerate = true`; setiap respons AJAX harus mengembalikan token baru.
- Harness tes ringan `php tests\run.php`; tidak ada `composer test`.
- Konvensi proyek: tanpa komentar kode baru; nama konstanta `SCREAMING_SNAKE_CASE`.
- Perubahan dilakukan bertahap dan diverifikasi tiap stage.

## Approaches

1. **Bertahap (dipilih).** Fondasi AJAX/CRUD + query/seeder/CSS dulu, lalu artikel + chart, lalu pohon + diagnosis tamu. Risiko regresi rendah, tiap stage bisa diuji.
2. **Big-bang.** Semua fitur sekaligus. Lebih cepat secara kalender, tetapi sulit mengisolasi bug pada CSRF, migrasi, dan query mining yang sudah rapuh.
3. **Mock UI.** Hanya tampilan modal/chart/pohon tanpa backend nyata. Tidak memenuhi permintaan seeder, query, dan data artikel.

## Resolved questions

- AJAX: POST + JSON + CSRF biasa, bukan antrean request.
- Pohon: multi-root berdasarkan nilai `akar`; token `parent` menjadi jalur cabang.
- `/diagnosa`: read-only untuk semua peran; penyimpanan hanya lewat tombol “Simpan riwayat” eksplisit.
- Migrasi: hanya menambah tabel artikel dan index; `database/diagnosis.sql` tetap sumber schema dasar.
- `DemoSeeder`: upsert berdasarkan natural key; tidak mereset tabel yang sudah berisi.
- Test lama: assertion CTA login-first dan halaman password khusus diupdate ke spek baru.
- Chart: scope sama dengan kartu; admin melihat global, user hanya `hasil` milik sendiri.

## Design decisions

### 1. CRUD modal + AJAX

- Endpoint POST existing dipertahankan, tetapi controller mengecek `$this->request->isAJAX()`.
- Respons JSON: `status`, `message`, `errors` (per field), `csrf`, dan `row` bila perlu. Error validasi memakai HTTP 422.
- `FormData` dari form modal tetap mengirim `csrf_field()`; token baru dari JSON dipakai ulang oleh JS ke semua input `csrf_test_name` dan meta tag.
- Satu file delegated JS `public/js/admin-crud.js` menangani submit, disable tombol saat pending, error summary yang bisa difokus, inline `invalid-feedback`, hide modal saat sukses, dan update baris tabel tanpa reload.
- Modal partial dibersihkan: id unik, hapus handler validasi dobel, perbaiki selector `formUpdatePenyakit`/`formUpdateSample`, perbaiki `name=" id_sample"` dan `name=" username"`, hapus flashdata mati.
- Profil dan password juga dimodalikan dengan AJAX; upload foto memakai `FormData`.
- `mining/kosong` dan `mining/mining` diubah dari GET destruktif ke POST + CSRF + AJAX.

### 2. Query dan seeder

- Perbaiki pemakaian kolom `users.name` yang tidak ada; pakai `username`/`fullname` atau alias `auth_groups.name AS name`.
- Ganti `SELECT * + getNumRows()` menjadi `COUNT(*)` di model penghitung.
- Batch insert dan preload entropy pada mining; hapus duplikat `sampleModel` mining yang memakai `TRUNCATE`.
- Cast id pada `whereIn`, tambah index untuk kolom yang difilter/digroupkan, dan batasi `like()` leading wildcard untuk dataset kecil atau pakai FULLTEXT untuk artikel.
- `DemoSeeder` upsert by natural key: groups/permissions/users, 6 gejala, 4 penyakit, 37 sample, 4 aturan, 5 hasil, kategori + 4 artikel (3 publish, 1 draft); tidak mereset data existing. Password memakai `PasswordHasher`.

### 3. Artikel

- Tabel baru: `artikel`, `kategori_artikel`, `artikel_kategori`; index `(status, published_at)` dan index FULLTEXT pada judul/ringkasan/konten.
- Model `artikelModel`, controller `Admin::artikel*` dengan CRUD AJAX modal, route `admin/artikel*`, menu sidebar “Artikel”.
- Halaman publik `/artikel` dan `/artikel/(:slug)` memakai kartu SB Admin; daftar artikel juga muncul sebagai ringkasan di landing.

### 4. Chart dashboard

- Chart.js 2.9.4 dimuat hanya di dashboard.
- Doughnut untuk komposisi gejala/penyakit/sample; bar untuk jumlah konsultasi per penyakit dari `hasil`.
- Kartu angka tetap menjadi sumber aksesibel; chart adalah tambahan, bukan pengganti. Warna memakai variabel SB Admin (`#4e73df`, `#1cc88a`, `#f6c23e`, `#e74a3b`).
- Scope chart mengikuti kartu: admin memakai agregat global, user hanya agregat `hasil` milik sendiri.

### 5. Pohon keputusan

- Service `DecisionTreeGraph` mengubah baris `decision_tree` menjadi node/edge: beberapa root sesuai nilai `akar`, token `parent` sebagai jalur gejala, `penyakit` sebagai daun.
- Render inline `<svg>` dengan `<path>` dan `<g>` yang bisa difokus; sertakan `<title>` per node dan fallback daftar bersarang untuk pembaca layar.
- Tabel existing dipindahkan ke detail `<details>` “Lihat aturan mentah”.
- Tidak ada library graph baru.

### 6. Diagnosis tamu

- Route publik `/diagnosa` dan `/diagnosa/proses` di luar group `login`.
- Controller publik memakai `DiagnosisRuleMatcher` (pure service) dan `penyakitModel->hasil()` read-only; form tetap read-only untuk user login.
- Tombol “Simpan riwayat” hanya muncul untuk user login dan memanggil endpoint `user/diagnosis` secara eksplisit.
- Landing CTA `mulai-diagnosis` diarahkan ke `/diagnosa`; hasil menampilkan kartu diagnosis + CTA login untuk tamu.
- Form tamu memakai id checkbox unik, error summary, dan CSRF.

### 7. Font/CSS

- Satu daftar weight Nunito yang sama di layout utama, auth, dan landing.
- Hapus jQuery slim duplikat; satukan ke satu jQuery full di footer.
- Tambah `public/css/app.css` untuk utilitas modal, chart, dan tree; semua warna memakai variabel SB Admin.

## Delivery stages

1. **Stage 1 — Fondasi:** perbaikan CSRF/JS/modal/query/CSS + migrasi tambahan & seeder. Verifikasi: `php tests\run.php`, `php -l`, `git diff --check`.
2. **Stage 2 — Artikel & chart:** migrasi artikel, CRUD, halaman publik, chart dashboard, seeder artikel. Verifikasi memakai perintah yang sama + source test untuk rute/view.
3. **Stage 3 — Pohon & tamu:** `DecisionTreeGraph`, svg tree, route dan view diagnosis tamu, update landing CTA. Verifikasi unit test service + seluruh harness.

## Testing strategy

- Tambah test untuk JSON branch controller (source-level bila harness belum bisa bootstrap CI4 penuh).
- Unit test murni untuk `DecisionTreeGraph`, `DiagnosisRuleMatcher` guest path, dan struktur `DemoSeeder`.
- Source test untuk rute baru, label/id unik, CSRF pada form AJAX, dan tidak ada GET destruktif.
- Update assertion lama: CTA `/diagnosa`, password dimodalikan, tidak ada `changePasswordModal`.
- Verifikasi manual-db tetap diperlukan untuk migrasi/seed karena harness tidak terhubung ke MySQL.

## Builder notes

- Jangan commit sampai diminta.
- Utamakan perubahan kecil dan bertahap; hentikan stage bila test merah.
- Jalankan Stage 1 → 2 → 3 berurutan; jangan mulai stage berikut sebelum test hijau.
