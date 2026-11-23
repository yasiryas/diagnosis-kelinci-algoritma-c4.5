# Stage 1 Foundation Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Ubah CRUD/profile/mining ke AJAX POST + JSON + CSRF, rapikan query dan aset, serta siapkan schema artikel + seeder demo tanpa mengubah SB Admin.

**Architecture:** Controller mempertahankan alur non-AJAX untuk fallback dan menambah cabang JSON saat `isAJAX()`. Satu file JS delegated menangani semua form bermarker `data-ajax-form`, memperbarui token CSRF dari respons, dan mengupdate baris tabel tanpa reload. Migration artikel dan `DemoSeeder` dibuat di stage ini agar stage berikut tinggal memakainya.

**Tech Stack:** CodeIgniter 4, Myth Auth, PHP 8, SB Admin 2, Bootstrap 4, jQuery, vanilla `fetch()`, MySQL/MariaDB.

**Spec:** `docs/superpowers/specs/2026-11-20-diagnosis-kelinci-expansion-design.md`

## Global Constraints

- AJAX POST biasa + JSON + CSRF tanpa reload; bukan antrean request.
- SB Admin 2 v4.1.3 + Bootstrap 4.5.3 + Nunito + Chart.js 2.9.4 sudah tersedia di `public/`.
- CSRF global aktif dengan `regenerate = true`; setiap respons AJAX harus mengembalikan token baru.
- Tanpa dependency npm/composer baru.
- Tanpa komentar kode baru; nama konstanta PHP `SCREAMING_SNAKE_CASE`.
- Verifikasi: `php tests\run.php`, `php -l`, `git diff --check`.
- Jangan commit sampai diminta.
- Upsert by natural key; jangan reset tabel yang sudah berisi.
- Migrasi hanya menambah tabel artikel; `database/diagnosis.sql` tetap sumber schema dasar.

## Review Focus

- CSRF token basi setelah regenerate → Task 1 JSON `csrf` + Task 2 JS refresh; test `testAjaxResponseRefreshesCsrfToken` dan `testAdminCrudJsRefreshesCsrf`.
- Error validasi tidak muncul di field yang salah → Task 1 `errors` per field + Task 2 error summary fokus; test `testAdminAjaxValidationReturnsFieldErrors` dan `testAdminCrudJsShowsInlineErrors`.
- Form tetap bisa dipakai tanpa JavaScript → Task 1/3/4 cabang non-AJAX mempertahankan redirect/flash; test `testAdminNonAjaxFallbackStillRedirects`.
- Mining merusak data lewat GET → Task 4 route POST + CSRF; test `testMiningRoutesUsePostForMutations`.
- Seeder di database berisi → Task 7 upsert natural key tanpa `TRUNCATE`/`DELETE`; test `testDemoSeederUpsertsWithoutReset`.

---

### Task 1: AJAX response contract dan Admin CRUD JSON

**Files:**
- Modify: `app/Controllers/BaseController.php`
- Modify: `app/Controllers/Admin.php:103-315`
- Test: `tests/AdminAjaxTest.php`

**Interfaces:**
- Consumes: `$this->request->isAJAX()`, `csrf_hash()`, `csrf_token()`.
- Produces: `BaseController::ajaxResponse(array $payload, int $status = 200)` yang mengembalikan JSON dengan keys `status`, `message`, `errors`, `csrf`, `row`.
- Produces: `Admin::tambahGejala`, `updateGejala`, `deleteGejala`, `tambahPenyakit`, `updatePenyakit`, `deletePenyakit`, `tambahSample`, `updateSample`, `deleteSample` mendukung JSON bila AJAX.

- [ ] **Step 1: Write the failing test**

```php
final class AdminAjaxTest
{
    public function testBaseControllerProvidesJsonResponseHelper(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/BaseController.php');
        expectTrue(strpos($source, 'function ajaxResponse(') !== false, 'BaseController butuh helper ajaxResponse.');
        expectTrue(strpos($source, "'csrf'") !== false, 'Respons AJAX harus mengembalikan csrf baru.');
    }

    public function testAdminCrudMethodsBranchOnAjax(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        foreach (['tambahGejala', 'updateGejala', 'deleteGejala', 'tambahPenyakit', 'updatePenyakit', 'deletePenyakit', 'tambahSample', 'updateSample', 'deleteSample'] as $method) {
            expectTrue(strpos($source, 'function ' . $method) !== false, "Method {$method} harus ada.");
        }
        expectTrue(substr_count($source, 'isAJAX()') >= 9, 'Semua CRUD admin perlu cabang isAJAX().');
    }

    public function testAdminAjaxValidationReturnsFieldErrors(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        expectTrue(strpos($source, "'errors' =>") !== false, 'Respons validasi AJAX harus memuat errors per field.');
        expectTrue(strpos($source, '422') !== false, 'Validasi AJAX harus memakai status 422.');
    }

    public function testAjaxResponseRefreshesCsrfToken(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/BaseController.php');
        expectTrue(strpos($source, 'csrf_hash()') !== false, 'Helper AJAX harus membaca csrf_hash().');
    }

    public function testAdminNonAjaxFallbackStillRedirects(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        expectTrue(strpos($source, "redirect()->to('admin/gejala')") !== false, 'Fallback non-AJAX gejala tetap redirect.');
        expectTrue(strpos($source, "redirect()->to('admin/datasample')") !== false, 'Fallback non-AJAX sample tetap redirect.');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests\run.php`
Expected: FAIL dengan pesan helper/cabang AJAX belum ada.

- [ ] **Step 3: Implement `ajaxResponse()` dan cabang AJAX di 9 method Admin**

Tambahkan method protected `ajaxResponse(array $payload, int $status = 200)` di `BaseController`. Gabungkan `status => $status < 400`, `csrf => csrf_hash()`, dan payload. Di tiap method Admin: validasi gagal + AJAX → `ajaxResponse(['message' => ..., 'errors' => $validation->getErrors()], 422)`; sukses + AJAX → `ajaxResponse(['message' => ..., 'row' => data yang disimpan])`; non-AJAX tetap memakai flashdata + redirect yang ada.

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests\run.php`
Expected: PASS semua test, termasuk test lama.

- [ ] **Step 5: Verify task**

Run: `php -l app/Controllers/BaseController.php; php -l app/Controllers/Admin.php; php tests\run.php; git diff --check`
Expected: tidak ada error PHP, suite hijau, tidak ada whitespace error.

### Task 2: JS delegated AJAX dan perbaikan modal CRUD

**Files:**
- Modify: `app/Views/templates/modal.php`
- Modify: `app/Views/templates/index.php`
- Create: `public/js/admin-crud.js`
- Test: `tests/AjaxModalTest.php`

**Interfaces:**
- Consumes: JSON keys dari Task 1.
- Produces: atribut `data-ajax-form`, `data-error-summary`, `data-row-id`, `data-ajax-url`, `data-ajax-method`; meta `csrf-token`; body `data-csrf-name`.
- Produces: `public/js/admin-crud.js` dengan handler `submit` delegated pada `form[data-ajax-form]` dan `click` delegated pada `[data-ajax-url]`.

- [ ] **Step 1: Write the failing test**

```php
final class AjaxModalTest
{
    public function testCrudFormsAreMarkedForAjax(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/templates/modal.php');
        expectTrue(strpos($source, 'data-ajax-form') !== false, 'Form CRUD perlu marker AJAX.');
        expectTrue(strpos($source, 'data-error-summary') !== false, 'Form CRUD perlu error summary.');
    }

    public function testModalIdsAreUnique(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/templates/modal.php');
        preg_match_all('/id="([^"]+)"/', $source, $matches);
        expectTrue(count($matches[1]) === count(array_unique($matches[1])), 'Id modal tidak boleh dobel.');
    }

    public function testAjaxScriptIsLoadedAfterSbAdmin(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/templates/index.php');
        expectTrue(strpos($source, 'js/admin-crud.js') !== false, 'Layout harus memuat admin-crud.js.');
        expectTrue(strpos($source, 'csrf-token') !== false, 'Layout harus memuat meta csrf-token.');
    }

    public function testAdminCrudJsRefreshesCsrf(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/public/js/admin-crud.js');
        expectTrue(strpos($source, "payload.csrf") !== false, 'JS harus memakai csrf dari respons.');
        expectTrue(strpos($source, 'FormData') !== false, 'JS harus mengirim FormData.');
    }

    public function testAdminCrudJsShowsInlineErrors(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/public/js/admin-crud.js');
        expectTrue(strpos($source, 'invalid-feedback') !== false, 'JS harus menampilkan invalid-feedback.');
        expectTrue(strpos($source, 'focus()') !== false, 'JS harus memindahkan fokus ke error summary.');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests\run.php`
Expected: FAIL karena marker/JS belum ada.

- [ ] **Step 3: Tambah marker, hapus duplikasi, dan tulis `admin-crud.js`**

Tambahkan `data-ajax-form` dan `data-error-summary` pada semua form CRUD di modal; beri `data-ajax-url`/`data-ajax-method` pada tombol aksi; samakan id label dan input. Di layout, hapus jQuery slim dari `<head>`, tambah meta `csrf-token`, atribut `data-csrf-name` pada `<body>`, dan script `js/admin-crud.js` setelah `sb-admin-2.min.js`. JS: `fetch` FormData, disable tombol saat pending, update meta + semua input CSRF dari `payload.csrf`, tampilkan error summary lalu `focus()`, isi `invalid-feedback` per key, reload/perbarui baris bertanda `data-row-id`, hide modal saat sukses. Tanpa komentar.

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests\run.php`
Expected: PASS.

- [ ] **Step 5: Verify task**

Run: `php -l app/Views/templates/modal.php; php -l app/Views/templates/index.php; php tests\run.php; git diff --check`
Expected: template valid, suite hijau.

### Task 3: Profil dan password lewat modal AJAX

**Files:**
- Modify: `app/Controllers/User.php:86-160`
- Modify: `app/Views/user/edit.php`
- Modify: `app/Views/user/changepassword.php`
- Modify: `app/Views/templates/modal.php`
- Modify: `tests/UiUsabilityTest.php`
- Test: `tests/ProfileAjaxTest.php`

**Interfaces:**
- Consumes: `BaseController::ajaxResponse()` dari Task 1 dan marker JS dari Task 2.
- Produces: modal `profileModal` dan `passwordModal`; `User::updateProfile()` dan `User::updatePassword()` mendukung JSON.

- [ ] **Step 1: Write the failing test**

```php
final class ProfileAjaxTest
{
    public function testProfileAndPasswordUseAjaxMarkedModals(): void
    {
        $modal = (string) file_get_contents(APP_ROOT . '/app/Views/templates/modal.php');
        expectTrue(strpos($modal, 'profileModal') !== false, 'Modal profil harus ada.');
        expectTrue(strpos($modal, 'passwordModal') !== false, 'Modal password harus ada.');
    }

    public function testUserUpdateMethodsBranchOnAjax(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/User.php');
        expectTrue(strpos($source, 'function updateProfile') !== false, 'updateProfile harus ada.');
        expectTrue(strpos($source, 'function updatePassword') !== false, 'updatePassword harus ada.');
        expectTrue(substr_count($source, 'isAJAX()') >= 2, 'update profil dan password perlu cabang AJAX.');
    }

    public function testPasswordPageNoLongerRequired(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/tests/UiUsabilityTest.php');
        expectTrue(strpos($source, 'testPasswordChangeUsesDedicatedPage') === false, 'Assertion lama harus diganti ke modal.');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests\run.php`
Expected: FAIL karena modal/branch belum ada dan assertion lama masih ada.

- [ ] **Step 3: Implementasi modal profil/password dan update test lama**

Tambahkan modal `profileModal` dan `passwordModal` dengan form bermarker dari Task 2; hapus `changePasswordModal` lama bila masih ada. Ubah `User::updateProfile()`/`updatePassword()` mengikuti pola AJAX Task 1. Ganti test lama `testPasswordChangeUsesDedicatedPage` dengan `testPasswordChangeUsesModal` yang memeriksa `passwordModal` dan tidak adanya halaman khusus sebagai satu-satunya jalur.

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests\run.php`
Expected: PASS.

- [ ] **Step 5: Verify task**

Run: `php -l app/Controllers/User.php; php -l app/Views/user/edit.php; php -l app/Views/user/changepassword.php; php tests\run.php; git diff --check`
Expected: suite hijau.

### Task 4: Mining mutasi memakai POST + CSRF

**Files:**
- Modify: `app/Config/Routes.php:69-73`
- Modify: `app/Controllers/Mining.php:37-60`
- Modify: `app/Views/admin/sample.php`
- Modify: `app/Views/admin/lapormining.php`
- Test: `tests/MiningAjaxTest.php`

**Interfaces:**
- Consumes: `BaseController::ajaxResponse()` dan JS marker dari Task 2.
- Produces: `POST mining/mining`, `POST mining/kosong`; `GET mining/hitung` tetap read-only.

- [ ] **Step 1: Write the failing test**

```php
final class MiningAjaxTest
{
    public function testMiningRoutesUsePostForMutations(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Config/Routes.php');
        expectTrue(strpos($source, "post('mining', 'Mining::mining')") !== false, 'mining harus POST.');
        expectTrue(strpos($source, "post('kosong', 'Mining::kosong')") !== false, 'kosong harus POST.');
        expectTrue(strpos($source, "get('hitung', 'Mining::hitung')") !== false, 'hitung tetap GET.');
    }

    public function testMiningMutationViewsPostCsrf(): void
    {
        foreach (['sample.php', 'lapormining.php'] as $file) {
            $source = (string) file_get_contents(APP_ROOT . '/app/Views/admin/' . $file);
            expectTrue(strpos($source, 'method="post"') !== false, "{$file} harus POST.");
            expectTrue(strpos($source, 'csrf_field()') !== false, "{$file} harus CSRF.");
        }
    }

    public function testMiningControllerHasAjaxBranch(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Mining.php');
        expectTrue(substr_count($source, 'isAJAX()') >= 2, 'mining dan kosong perlu cabang AJAX.');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests\run.php`
Expected: FAIL karena route masih GET.

- [ ] **Step 3: Implementasi POST dan branch JSON**

Ubah dua route mutasi ke `post`. Di `Mining::mining()`/`kosong()`, bungkus operasi dengan transaksi yang ada; AJAX → `ajaxResponse(['message' => ...])`, non-AJAX → redirect seperti sekarang. Ubah tombol di view menjadi form POST bermarker `data-ajax-form` dengan `csrf_field()`.

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests\run.php`
Expected: PASS.

- [ ] **Step 5: Verify task**

Run: `php -l app/Config/Routes.php; php -l app/Controllers/Mining.php; php tests\run.php; git diff --check`
Expected: suite hijau.

### Task 5: Query cleanup

**Files:**
- Modify: `app/Models/listModel.php`
- Modify: `app/Models/gejalaModel.php`
- Modify: `app/Models/penyakitModel.php`
- Modify: `app/Models/decisionTreeModel.php`
- Test: `tests/QueryCleanupTest.php`

**Interfaces:**
- Consumes: struktur tabel dari `database/diagnosis.sql`.
- Produces: method count mengembalikan `int` dari `SELECT COUNT(*)`, pencarian user memakai `fullname`.

- [ ] **Step 1: Write the failing test**

```php
final class QueryCleanupTest
{
    public function testCountMethodsUseCountStar(): void
    {
        foreach (['listModel.php', 'gejalaModel.php', 'penyakitModel.php', 'decisionTreeModel.php'] as $file) {
            $source = (string) file_get_contents(APP_ROOT . '/app/Models/' . $file);
            expectTrue(strpos($source, 'COUNT(*)') !== false, "{$file} harus memakai COUNT(*).");
        }
    }

    public function testUserSearchUsesFullname(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Models/listModel.php');
        expectTrue(strpos($source, "orLike('fullname'") !== false, 'Pencarian user harus memakai fullname.');
        expectTrue(strpos($source, "orLike('name'") === false, 'Kolom users.name tidak ada.');
    }

    public function testCountMethodsCastIds(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Models/listModel.php');
        expectTrue(strpos($source, '(int) $id') !== false, 'Parameter id harus di-cast.');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests\run.php`
Expected: FAIL karena masih `SELECT *`.

- [ ] **Step 3: Implementasi query**

Ganti `SELECT * + getNumRows()` pada method count dengan `SELECT COUNT(*) AS total` lalu kembalikan `(int) row->total`. Perbaiki `search()` di `listModel` memakai `fullname`. Pastikan semua id di-binding sebagai `(int)`. Jangan mengubah method mining yang memang butuh `SELECT *`.

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests\run.php`
Expected: PASS.

- [ ] **Step 5: Verify task**

Run: `php -l app/Models/listModel.php; php -l app/Models/gejalaModel.php; php -l app/Models/penyakitModel.php; php -l app/Models/decisionTreeModel.php; php tests\run.php; git diff --check`
Expected: suite hijau.

### Task 6: Font dan aset CSS konsisten

**Files:**
- Modify: `app/Views/templates/index.php`
- Modify: `app/Views/landing.php`
- Create: `public/css/app.css`
- Test: `tests/ThemeAssetTest.php`

**Interfaces:**
- Consumes: token warna SB Admin dari spec.
- Produces: `public/css/app.css` dengan variabel `--dk-primary`, `--dk-primary-dark`, `--dk-light`, `--dk-border`; utility `.dk-error-summary`, `.dk-tree`, `.dk-chart-card`.

- [ ] **Step 1: Write the failing test**

```php
final class ThemeAssetTest
{
    public function testLayoutUsesOneJquery(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/templates/index.php');
        expectTrue(strpos($source, 'jquery-3.2.1.slim.min.js') === false, 'jQuery slim duplikat harus dihapus.');
        expectTrue(substr_count($source, 'jquery.min.js') === 1, 'Hanya satu jQuery full yang boleh dimuat.');
    }

    public function testLayoutAndLandingShareNunitoWeights(): void
    {
        foreach (['app/Views/templates/index.php', 'app/Views/landing.php'] as $file) {
            $source = (string) file_get_contents(APP_ROOT . '/' . $file);
            expectTrue(strpos($source, 'Nunito:200,300,400,600,700,800,900') !== false, "{$file} harus memakai weight Nunito yang sama.");
        }
    }

    public function testAppCssDefinesThemeTokens(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/public/css/app.css');
        foreach (['--dk-primary', '--dk-primary-dark', '--dk-light', '--dk-border'] as $token) {
            expectTrue(strpos($source, $token) !== false, "{$token} harus ada.");
        }
    }

    public function testAppCssIsLoaded(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/templates/index.php');
        expectTrue(strpos($source, 'css/app.css') !== false, 'Layout harus memuat app.css.');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests\run.php`
Expected: FAIL karena app.css belum ada dan slim jQuery masih ada.

- [ ] **Step 3: Implementasi font/CSS**

Samakan query Google Fonts di layout dan landing ke `Nunito:200,300,400,600,700,800,900`. Hapus tag jQuery slim di `<head>`. Buat `public/css/app.css` berisi `:root` dengan token SB Admin dan utility yang dipakai stage berikut. Tambahkan link `css/app.css` setelah `sb-admin-2.min.css`.

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests\run.php`
Expected: PASS.

- [ ] **Step 5: Verify task**

Run: `php -l app/Views/templates/index.php; php tests\run.php; git diff --check`
Expected: suite hijau.

### Task 7: Migration artikel dan DemoSeeder upsert

**Files:**
- Create: `app/Database/Migrations/2026-11-20-000001_CreateArticleTables.php`
- Create: `app/Database/Seeds/DemoSeeder.php`
- Test: `tests/SeederTest.php`

**Interfaces:**
- Consumes: `PasswordHasher::hash()`, tabel domain dari `database/diagnosis.sql`.
- Produces: tabel `kategori_artikel`, `artikel`, `artikel_kategori`; `DemoSeeder::run()` callable berulang kali.

- [ ] **Step 1: Write the failing test**

```php
final class SeederTest
{
    public function testArticleMigrationCreatesRequiredTables(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Database/Migrations/2026-11-20-000001_CreateArticleTables.php');
        foreach (['kategori_artikel', 'artikel', 'artikel_kategori'] as $table) {
            expectTrue(strpos($source, "'{$table}'") !== false, "Migration harus membuat {$table}.");
        }
        expectTrue(strpos($source, 'FULLTEXT') !== false, 'Migration harus punya index FULLTEXT artikel.');
    }

    public function testDemoSeederUpsertsWithoutReset(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Database/Seeds/DemoSeeder.php');
        expectTrue(strpos($source, 'TRUNCATE') === false, 'Seeder tidak boleh TRUNCATE.');
        expectTrue(strpos($source, 'DELETE FROM') === false, 'Seeder tidak boleh DELETE.');
        expectTrue(strpos($source, 'where(') !== false, 'Seeder harus mengecek natural key.');
    }

    public function testDemoSeederHasCompleteDomainRows(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Database/Seeds/DemoSeeder.php');
        foreach (['seedGejala', 'seedPenyakit', 'seedSample', 'seedDecisionTree', 'seedHasil', 'seedArticles'] as $method) {
            expectTrue(strpos($source, 'function ' . $method) !== false, "Seeder butuh {$method}.");
        }
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests\run.php`
Expected: FAIL karena migration/seeder belum ada.

- [ ] **Step 3: Implementasi migration dan seeder**

Migration `up()` membuat tiga tabel artikel dengan kolom `id`, `slug`, `judul`, `ringkasan`, `konten`, `status`, `published_at`, `created_at`, `updated_at`, serta tabel pivot; `down()` menghapusnya berurutan. `DemoSeeder::run()` memanggil `seedAuthGroups()`, `seedUsers()`, `seedGejala()`, `seedPenyakit()`, `seedSample()`, `seedDecisionTree()`, `seedHasil()`, `seedArticles()`; tiap method mencari berdasarkan natural key lalu `insert` bila belum ada atau `update` bila ada. Isi 6 gejala, 4 penyakit, 37 sample, 4 aturan, 5 hasil, 4 kategori/artikel (3 publish, 1 draft) sesuai spec. Password memakai `PasswordHasher::hash('123')`.

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests\run.php`
Expected: PASS.

- [ ] **Step 5: Verify task**

Run: `php -l app/Database/Migrations/2026-11-20-000001_CreateArticleTables.php; php -l app/Database/Seeds/DemoSeeder.php; php tests\run.php; git diff --check`
Expected: suite hijau.

### Task 8: Final Stage 1 verification

**Files:**
- Test: `tests/run.php`

**Interfaces:**
- Consumes: semua task sebelumnya.
- Produces: Stage 1 siap dipakai Stage 2.

- [ ] **Step 1: Run full suite**

Run: `php tests\run.php`
Expected: `N tests, 0 failures`.

- [ ] **Step 2: Lint all changed PHP files**

Run: `for f in $(git diff --name-only -- '*.php'); do php -l "$f" || exit 1; done`
Expected: tidak ada error sintaks.

- [ ] **Step 3: Check whitespace**

Run: `git diff --check`
Expected: hanya warning LF→CRLF yang sudah dikenal; tidak ada error.

- [ ] **Step 4: Record Stage 1 result**

Tulis ke ledger workspace: `Stage 1 complete — <jumlah> tests, 0 failures; php -l clean; git diff --check clean`. Jangan commit.
