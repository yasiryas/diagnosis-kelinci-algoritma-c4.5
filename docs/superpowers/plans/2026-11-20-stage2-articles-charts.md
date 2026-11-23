# Stage 2 Articles and Charts Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Tambah manajemen artikel admin + halaman baca publik + ringkasan landing, dan chart dashboard dari data yang sudah ada.

**Architecture:** `artikelModel` menyimpan semua query artikel/kategori. Admin memakai pola AJAX Task 1–2 (`ajaxResponse`, `data-ajax-form`, `admin-crud.js`) dengan marker baris `data-row-id`/`data-field`. Halaman publik memakai layout mandiri. Chart memakai `DashboardChartData` murni untuk payload dan `dashboard-charts.js` yang dimuat hanya di dashboard lewat section `scripts`.

**Tech Stack:** CodeIgniter 4, PHP 8, SB Admin 2 v4.1.3, Bootstrap 4.5.3, Chart.js 2.9.4 lokal, vanilla `fetch()`, MySQL/MariaDB.

**Spec:** `docs/superpowers/specs/2026-11-20-diagnosis-kelinci-expansion-design.md`

## Global Constraints

- Tanpa dependency npm/composer baru; Chart.js 2.9.4 sudah ada di `public/vendor/chart.js/Chart.min.js`.
- CSRF global aktif dengan `regenerate = true`; setiap respons AJAX harus mengembalikan token baru lewat `ajaxResponse()`.
- Tanpa komentar kode baru; nama konstanta PHP `SCREAMING_SNAKE_CASE`.
- Verifikasi: `php tests\run.php`, `php -l`, `git diff --check`.
- Jangan commit sampai diminta.
- Tidak mengubah tabel artikel dari Stage 1; `database/diagnosis.sql` tetap sumber schema dasar.
- Semua teks antarmuka baru berbahasa Indonesia.

## Review Focus

- Draft artikel bocor ke halaman publik → `ArticleModelTest::testPublishedQueriesForcePublishStatus`.
- Route detail memakai placeholder `(:slug)` yang tidak dikenal CI4 → `PublicArticleTest::testArticleRoutesUseSlugPattern`.
- Slug duplikat saat tambah/ubah artikel → `AdminArticleTest::testArticleCrudReturnsFieldErrorsForDuplicateSlug`.
- Baris tabel tidak tersinkron setelah AJAX → `AdminArticleTest::testArticleViewExposesRowMarkers`.
- User biasa melihat agregat konsultasi global → `DashboardChartTest::testChartQueryAcceptsOptionalUserId`.
- Form artikel tidak bisa dipakai tanpa JS → `AdminArticleTest::testArticleNonAjaxFallbackStillRedirects`.

---

### Task 1: Query model artikel

**Files:**
- Create: `app/Models/artikelModel.php`
- Test: `tests/ArticleModelTest.php`

**Interfaces:**
- Produces: `artikelModel::getPublished(int $limit = 0): array`, `getPublishedBySlug(string $slug): ?array`, `getAdminArticles(): array`, `getCategories(): array`, `getCategoryOptions(): array`, `getCategoryIds(int $articleId): array`, `findById(int $id): ?array`, `findBySlug(string $slug): ?array`, `slugExists(string $slug, ?int $excludeId = null): bool`, `saveCategories(int $articleId, array $categoryIds): void`.
- Baris `getPublished()`/`getAdminArticles()` memuat keys `id`, `judul`, `slug`, `ringkasan`, `konten`, `status`, `published_at`, `nama_kategori`.

- [ ] **Step 1: Write the failing test**

```php
final class ArticleModelTest
{
    public function testArticleModelDefinesRequiredQueries(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Models/artikelModel.php');
        foreach (['getPublished', 'getPublishedBySlug', 'getAdminArticles', 'getCategories', 'getCategoryOptions', 'getCategoryIds', 'findById', 'findBySlug', 'slugExists', 'saveCategories'] as $method) {
            expectTrue(strpos($source, 'function ' . $method) !== false, "artikelModel butuh {$method}.");
        }
    }

    public function testPublishedQueriesForcePublishStatus(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Models/artikelModel.php');
        expectTrue(strpos($source, "'publish'") !== false, 'Query publik harus memfilter status publish.');
        expectTrue(strpos($source, 'artikel_kategori') !== false, 'Query artikel harus menggabungkan kategori lewat pivot.');
    }

    public function testSaveCategoriesUsesArticlePivot(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Models/artikelModel.php');
        expectTrue(strpos($source, "'artikel_kategori'") !== false, 'Pivot artikel_kategori harus dipakai.');
        expectTrue(strpos($source, '(int) $articleId') !== false, 'Id artikel harus di-cast.');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests\run.php`
Expected: FAIL karena `artikelModel.php` belum ada.

- [ ] **Step 3: Implementasi `artikelModel`**

Buat class `artikelModel extends Model` dengan `$table = 'artikel'`. Query memakai `$this->db->table('artikel')` + `join('artikel_kategori', ...)` + `join('kategori_artikel', ...)`, `select('artikel.id, artikel.judul, artikel.slug, artikel.ringkasan, artikel.konten, artikel.status, artikel.published_at, kategori_artikel.nama AS nama_kategori, kategori_artikel.id AS kategori_id')`. `getPublished()` menambah `where('artikel.status', 'publish')`, `orderBy('artikel.published_at', 'DESC')`, dan `limit()` bila `$limit > 0`. `getPublishedBySlug()` memakai filter status + slug yang sama dan mengembalikan `getFirstRowArray()` atau null. `getCategoryIds()` mengembalikan array int dari pivot. `saveCategories()` menghapus pivot lama untuk id tersebut lalu insert satu baris per kategori. `slugExists()` mengecualikan `$excludeId` bila tidak null.

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests\run.php`
Expected: PASS.

- [ ] **Step 5: Verify task**

Run: `php -l app/Models/artikelModel.php; php tests\run.php; git diff --check`
Expected: tidak ada error sintaks, suite hijau.

### Task 2: CRUD artikel admin

**Files:**
- Create: `app/Services/ArticleSlug.php`
- Modify: `app/Controllers/Admin.php:5-34`
- Modify: `app/Config/Routes.php:48-67`
- Modify: `app/Views/admin/artikel.php` (baru)
- Modify: `app/Views/templates/modal.php`
- Modify: `app/Views/templates/sidebar.php:63-69`
- Test: `tests/AdminArticleTest.php`

**Interfaces:**
- Consumes: `BaseController::ajaxResponse()`, `artikelModel` dari Task 1, marker `data-ajax-form` dan `admin-crud.js`.
- Produces: `ArticleSlug::fromJudul(string $judul): string`, `ArticleSlug::unique(string $slug, callable $exists): string`; `Admin::artikel`, `tambahArtikel`, `updateArtikel`, `deleteArtikel`; rute `admin/artikel`, `admin/tambahartikel`, `admin/updateartikel`, `admin/deleteartikel`; modal `artikelModal`, `editArtikelModal`, `deleteArtikelModal`.

- [ ] **Step 1: Write the failing test**

```php
final class AdminArticleTest
{
    public function testArticleCrudMethodsBranchOnAjax(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        foreach (['artikel', 'tambahArtikel', 'updateArtikel', 'deleteArtikel'] as $method) {
            expectTrue(strpos($source, 'function ' . $method) !== false, "Admin butuh {$method}.");
        }
        expectTrue(substr_count($source, 'isAJAX()') >= 12, 'CRUD artikel perlu cabang isAJAX().');
    }

    public function testArticleCrudReturnsFieldErrorsForDuplicateSlug(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        expectTrue(strpos($source, "'errors' => ['slug'") !== false, 'Slug duplikat harus mengembalikan errors.slug.');
        expectTrue(strpos($source, "'slugExists'") !== false || strpos($source, 'slugExists(') !== false, 'Controller harus memakai slugExists.');
    }

    public function testArticleViewExposesRowMarkers(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/admin/artikel.php');
        expectTrue(strpos($source, 'data-row-id=') !== false, 'Baris artikel butuh data-row-id.');
        foreach (['judul', 'slug', 'status', 'kategori'] as $field) {
            expectTrue(strpos($source, 'data-field="' . $field . '"') !== false, "Baris artikel butuh data-field {$field}.");
        }
    }

    public function testArticleModalsUseAjaxMarkers(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/templates/modal.php');
        foreach (['artikelModal', 'editArtikelModal', 'deleteArtikelModal'] as $id) {
            expectTrue(strpos($source, 'id="' . $id . '"') !== false, "Modal {$id} harus ada.");
        }
        expectTrue(substr_count($source, 'data-ajax-form') >= 12, 'Semua form artikel harus bermarker AJAX.');
    }

    public function testArticleNonAjaxFallbackStillRedirects(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        expectTrue(substr_count($source, "redirect()->to('admin/artikel')") >= 3, 'Fallback non-AJAX artikel tetap redirect.');
    }

    public function testArticleSlugService(): void
    {
        require_once APP_ROOT . '/app/Services/ArticleSlug.php';
        $service = new App\Services\ArticleSlug();
        expectSame('cara-merawat-kelinci-sembelit', $service->fromJudul('Cara Merawat Kelinci Sembelit'), 'Slug judul salah.');
        expectSame('nutrisi-untuk-kelinci-2', $service->unique('nutrisi-untuk-kelinci', static function (string $slug): bool {
            return $slug === 'nutrisi-untuk-kelinci' || $slug === 'nutrisi-untuk-kelinci-2';
        }), 'Slug unik salah.');
        expectSame('judul-baru', $service->fromJudul('Judul, Baru!'), 'Karakter bukan kata harus dibuang.');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests\run.php`
Expected: FAIL karena service, method, dan view belum ada.

- [ ] **Step 3: Implementasi `ArticleSlug` dan method Admin**

`ArticleSlug::fromJudul()` lowercase, ganti karakter non-alfanumerik jadi `-`, trim `-`, collapse `-` berulang. `ArticleSlug::unique()` mencoba `$slug`, lalu `$slug-2`, `$slug-3`, ... sampai callable `$exists` mengembalikan false. Di `Admin::__construct()` tambahkan `$this->artikelModel = new artikelModel();`. Method `artikel()` mem-paginate 8 artikel. Method tambah/ubah: baca `judul`, `slug`, `ringkasan`, `konten`, `status`, `kategori_id`, `published_at`; slug kosong → `fromJudul(judul)`; validasi judul/ringkasan/konten/status/kategori dengan `$this->validate()`; duplikat slug → `ajaxResponse` dengan argumen pertama berisi `'errors' => ['slug' => ...]` secara inline dan status 422; publish dengan `published_at` kosong → `date('Y-m-d H:i:s')`, draft → null; simpan artikel lalu `saveCategories()`; AJAX sukses → `ajaxResponse` dengan `row` berisi keys `id`, `judul`, `slug`, `status`, `kategori`. Method delete menghapus pivot, lalu artikel, lalu `ajaxResponse` dengan `row => ['id' => $id]`; non-AJAX tetap flashdata + redirect `admin/artikel`.

- [ ] **Step 4: Tambah rute, menu, view, dan modal**

Rute di group `admin`: `get('artikel', 'Admin::artikel')`, `post('tambahartikel', 'Admin::tambahArtikel')`, `post('updateartikel', 'Admin::updateArtikel')`, `post('deleteartikel', 'Admin::deleteArtikel')`. Sidebar: item “Artikel” setelah Decision Tree dengan aktif bila `$title === 'Data Artikel'`. View `admin/artikel.php`: tabel responsif, search GET, paginate, tombol edit/hapus dengan class `.btn-edit-artikel`/`.btn-delete-artikel` dan `data-row-id`, `<tr data-row-id>` + `<td data-field="...">`. Modal: form tambah/ubah/delete dengan `csrf_field()`, `data-ajax-form`, `data-error-summary`, id unik, label untuk tiap input, select kategori dari `getCategoryOptions()`, textarea konten, select status. Script jQuery di modal mengisi field dari tombol dan men-set `dataset.rowId` form sebelum `modal('show')`.

- [ ] **Step 5: Run test to verify it passes**

Run: `php tests\run.php`
Expected: PASS.

- [ ] **Step 6: Verify task**

Run: `php -l app/Services/ArticleSlug.php; php -l app/Controllers/Admin.php; php -l app/Config/Routes.php; php -l app/Views/admin/artikel.php; php -l app/Views/templates/modal.php; php tests\run.php; git diff --check`
Expected: suite hijau.

### Task 3: Halaman artikel publik dan ringkasan landing

**Files:**
- Create: `app/Controllers/Artikel.php`
- Create: `app/Views/templates/public.php`
- Create: `app/Views/artikel/index.php`
- Create: `app/Views/artikel/show.php`
- Modify: `app/Config/Routes.php:32-33`
- Modify: `app/Controllers/Home.php:12-17`
- Modify: `app/Views/landing.php:445-446`
- Test: `tests/PublicArticleTest.php`

**Interfaces:**
- Consumes: `artikelModel::getPublished()`, `getPublishedBySlug()` dari Task 1.
- Produces: `Artikel::index()`, `Artikel::show(string $slug)`, rute `artikel` dan `artikel/{slug}`, layout `templates/public`, variabel view `$artikel` (daftar) dan `$item` (detail), variabel landing `$artikelRingkasan`.

- [ ] **Step 1: Write the failing test**

```php
final class PublicArticleTest
{
    public function testArticleRoutesUseSlugPattern(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Config/Routes.php');
        expectTrue(strpos($source, "get('artikel', 'Artikel::index')") !== false, 'Rute daftar artikel harus ada.');
        expectTrue(strpos($source, "get('artikel/{slug}', 'Artikel::show/\$1')") !== false, 'Rute detail harus memakai placeholder {slug}.');
        expectTrue(strpos($source, "get('artikel/(:slug)'") === false, 'Placeholder (:slug) tidak dikenal CI4.');
    }

    public function testPublicArticleViewsUseStandaloneLayout(): void
    {
        foreach (['app/Views/artikel/index.php', 'app/Views/artikel/show.php'] as $file) {
            $source = (string) file_get_contents(APP_ROOT . '/' . $file);
            expectTrue(strpos($source, "\$this->extend('templates/public')") !== false, "{$file} harus memakai layout publik.");
        }
        $layout = (string) file_get_contents(APP_ROOT . '/app/Views/templates/public.php');
        expectTrue(strpos($layout, 'css/app.css') !== false, 'Layout publik harus memuat app.css.');
        expectTrue(strpos($layout, 'templates/sidebar') === false, 'Layout publik tidak boleh memuat sidebar admin.');
    }

    public function testLandingShowsArticleSummary(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/landing.php');
        expectTrue(strpos($source, '$artikelRingkasan') !== false, 'Landing harus merender ringkasan artikel.');
        expectTrue(strpos($source, "base_url('artikel/") !== false, 'Ringkasan harus menaut ke detail artikel.');
        $controller = (string) file_get_contents(APP_ROOT . '/app/Controllers/Home.php');
        expectTrue(strpos($controller, 'getPublished(3)') !== false, 'Home harus memuat tiga artikel terbaru.');
    }

    public function testArticleDetailUsesPublishedQueryAndReturns404(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Controllers/Artikel.php');
        expectTrue(strpos($source, 'getPublishedBySlug') !== false, 'Detail harus memakai query publik.');
        expectTrue(strpos($source, 'setStatusCode(404)') !== false, 'Artikel tidak ditemukan harus 404.');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests\run.php`
Expected: FAIL karena controller/view/rute publik belum ada.

- [ ] **Step 3: Implementasi controller, rute, dan layout publik**

`Artikel::__construct()` membuat `artikelModel`. `index()` mengirim `['title' => 'Artikel', 'artikel' => $this->artikelModel->getPublished()]` ke `artikel/index`. `show(string $slug)` mengambil `getPublishedBySlug($slug)`; null → `$this->response->setStatusCode(404)` + view `artikel/show` dengan `$item = null`; ada → kirim judul sebagai title. Rute ditaruh di luar group login/admin. `templates/public.php`: HTML mandiri dengan Nunito weight sama seperti layout utama, `css/sb-admin-2.min.css`, `css/app.css`, header berisi brand + tautan “Mulai diagnosis” dan “Masuk”, `renderSection('page-content')`, footer, lalu jQuery + Bootstrap bundle.

- [ ] **Step 4: Implementasi view artikel dan ringkasan landing**

`artikel/index.php`: kartu responsif per artikel berisi judul, tanggal publish, ringkasan, nama kategori, tautan detail; empty state bila kosong. `artikel/show.php`: kartu dengan judul, metadata, konten dalam paragraf terescaped, tautan kembali ke `/artikel`; bila `$item === null`, tampilkan pesan tidak ditemukan. `Home::landing()` menambah `'artikelRingkasan' => $this->artikelModel->getPublished(3)`, dengan `$this->artikelModel` dibuat di constructor. Landing: section baru “Artikel terbaru” setelah `#cara-kerja` berisi kartu ringan dan tautan “Lihat semua artikel”.

- [ ] **Step 5: Run test to verify it passes**

Run: `php tests\run.php`
Expected: PASS.

- [ ] **Step 6: Verify task**

Run: `php -l app/Controllers/Artikel.php; php -l app/Controllers/Home.php; php -l app/Views/templates/public.php; php -l app/Views/artikel/index.php; php -l app/Views/artikel/show.php; php -l app/Views/landing.php; php tests\run.php; git diff --check`
Expected: suite hijau.

### Task 4: Chart dashboard

**Files:**
- Create: `app/Services/DashboardChartData.php`
- Create: `public/js/dashboard-charts.js`
- Modify: `app/Models/listModel.php:28-38`
- Modify: `app/Controllers/Admin.php:36-47`
- Modify: `app/Controllers/User.php:35-63`
- Modify: `app/Views/templates/index.php:85-89`
- Modify: `app/Views/user/index.php`
- Test: `tests/DashboardChartTest.php`

**Interfaces:**
- Consumes: `countGejala()`, `countPenyakit()`, `countDataSample()`, `countAllHasil()`, `countHasil()`.
- Produces: `DashboardChartData::payload(array $composition, array $consultations): array`, `listModel::countHasilPerPenyakit(?int $idUser = null): array`, variabel view `$chartData`, `<canvas id="dkCompositionChart">`, `<canvas id="dkConsultationChart">`, section `scripts` di layout.

- [ ] **Step 1: Write the failing test**

```php
final class DashboardChartTest
{
    public function testChartQueryAcceptsOptionalUserId(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Models/listModel.php');
        expectTrue(strpos($source, 'function countHasilPerPenyakit(?int $idUser = null): array') !== false, 'Query konsultasi per penyakit harus menerima id user opsional.');
        expectTrue(strpos($source, 'COUNT(*)') !== false, 'Query harus memakai COUNT(*).');
        expectTrue(strpos($source, 'GROUP BY penyakit') !== false, 'Query harus mengelompokkan per penyakit.');
    }

    public function testAdminAndUserPassDifferentScope(): void
    {
        $admin = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        $user = (string) file_get_contents(APP_ROOT . '/app/Controllers/User.php');
        expectTrue(strpos($admin, 'countHasilPerPenyakit()') !== false, 'Admin memakai agregat global.');
        expectTrue(strpos($user, 'countHasilPerPenyakit($id_user)') !== false, 'User memakai agregat milik sendiri.');
    }

    public function testChartDataServiceBuildsPayload(): void
    {
        require_once APP_ROOT . '/app/Services/DashboardChartData.php';
        $data = App\Services\DashboardChartData::payload(
            ['gejala' => 6, 'penyakit' => 4, 'sample' => 37],
            [['penyakit' => 'Coccidiosis', 'total' => 3]]
        );
        expectSame(['Gejala', 'Penyakit', 'Sample'], $data['doughnut']['labels'], 'Label doughnut salah.');
        expectSame([6, 4, 37], $data['doughnut']['values'], 'Nilai doughnut salah.');
        expectSame(['Coccidiosis'], $data['bar']['labels'], 'Label bar salah.');
        expectSame([3], $data['bar']['values'], 'Nilai bar salah.');
        expectSame('#4e73df', $data['doughnut']['colors'][0], 'Warna harus memakai token SB Admin.');
    }

    public function testChartsRenderOnlyOnDashboard(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Views/user/index.php');
        expectTrue(strpos($source, 'id="dkCompositionChart"') !== false, 'Canvas doughnut harus ada.');
        expectTrue(strpos($source, 'id="dkConsultationChart"') !== false, 'Canvas bar harus ada.');
        expectTrue(strpos($source, 'window.dkChartData') !== false, 'View harus meneruskan payload chart.');
        $layout = (string) file_get_contents(APP_ROOT . '/app/Views/templates/index.php');
        expectTrue(strpos($layout, "renderSection('scripts')") !== false, 'Layout harus membuka section scripts.');
        expectTrue(strpos($layout, 'Chart.min.js') === false, 'Chart.js tidak boleh dimuat global.');
        $js = (string) file_get_contents(APP_ROOT . '/public/js/dashboard-charts.js');
        expectTrue(strpos($js, 'new Chart(') !== false, 'JS harus membuat chart.');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests\run.php`
Expected: FAIL karena service/JS/query belum ada.

- [ ] **Step 3: Implementasi query dan service**

`listModel::countHasilPerPenyakit(?int $idUser = null): array`: `SELECT penyakit, COUNT(*) AS total FROM hasil`, tambah `WHERE id_user = ?` dengan `(int) $idUser` bila tidak null, `GROUP BY penyakit ORDER BY total DESC`, kembalikan array asosiatif. `DashboardChartData` dengan konstanta `DOUGHNUT_COLORS` (`#4e73df`, `#1cc88a`, `#f6c23e`) dan `BAR_COLOR` (`#e74a3b`); `payload()` memetakan keys `gejala/penyakit/sample` ke label dan tiap baris konsultasi ke `labels`/`values`.

- [ ] **Step 4: Implementasi controller, view, dan aset**

`Admin::index()` dan `User::index()` menambah key `chartData` dari `DashboardChartData::payload(...)`; admin memanggil `countHasilPerPenyakit()` tanpa argumen, user memanggil `countHasilPerPenyakit($id_user)`. Layout `templates/index.php` menambah `<?= $this->renderSection('scripts'); ?>` setelah `admin-crud.js`. View `user/index.php` menambah dua kartu `.dk-chart-card` dengan canvas di dalamnya dan section `scripts` berisi `window.dkChartData = <?= json_encode($chartData) ?>;`, tag Chart.js lokal, lalu `js/dashboard-charts.js`. `dashboard-charts.js`: baca `window.dkChartData`, inisialisasi doughnut dan bar hanya bila canvas ada, pakai warna dari payload, `maintainAspectRatio: false`, tanpa komentar.

- [ ] **Step 5: Run test to verify it passes**

Run: `php tests\run.php`
Expected: PASS.

- [ ] **Step 6: Verify task**

Run: `php -l app/Services/DashboardChartData.php; php -l app/Models/listModel.php; php -l app/Controllers/Admin.php; php -l app/Controllers/User.php; php -l app/Views/templates/index.php; php -l app/Views/user/index.php; php tests\run.php; git diff --check`
Expected: suite hijau.

### Task 5: Final Stage 2 verification

**Files:**
- Modify: `.superpowers/sdd/2026-11-20-stage1-foundation/progress.md` (tambah catatan Stage 2)

**Interfaces:**
- Consumes: semua task Stage 2.

- [ ] **Step 1: Run full suite**

Run: `php tests\run.php`
Expected: `N tests, 0 failures`.

- [ ] **Step 2: Lint semua file PHP yang berubah**

Run: `for f in $(git diff --name-only -- '*.php'); do php -l "$f" || exit 1; done`
Expected: tidak ada error sintaks.

- [ ] **Step 3: Check whitespace**

Run: `git diff --check`
Expected: tidak ada error.

- [ ] **Step 4: Catat hasil Stage 2**

Tulis ke ledger: `Stage 2 complete — <jumlah> tests, 0 failures; php -l clean; git diff --check clean`. Jangan commit.
