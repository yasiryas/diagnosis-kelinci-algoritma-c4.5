# Stage 3 Decision Tree Graph and Guest Diagnosis Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Render `decision_tree` sebagai SVG pohon yang bisa diakses, dan izinkan tamu mencoba diagnosis tanpa akun tanpa menulis ke `hasil`.

**Architecture:** `DecisionTreeGraph` adalah service murni yang mengubah baris rule menjadi node/edge dan SVG inline; `Admin::decisionTree` hanya menyiapkan data dan menyerahkan render ke view. Alur tamu memakai controller publik `Diagnosis` yang memakai `DiagnosisRuleMatcher` dan `penyakitModel::hasil()` read-only; penyimpanan riwayat tetap lewat POST eksplisit `user/diagnosis` untuk user login.

**Tech Stack:** CodeIgniter 4, PHP 8, Myth Auth, SB Admin 2 v4.1.3, Bootstrap 4.5.3, SVG inline tanpa library graph baru, harness `php tests\run.php`.

**Spec:** `docs/superpowers/specs/2026-11-20-diagnosis-kelinci-expansion-design.md`

## Global Constraints

- CodeIgniter 4 + Myth Auth, PHP, MySQL/MariaDB.
- CSRF global aktif dengan `regenerate = true`; setiap form POST memakai `csrf_field()`.
- Tanpa dependency npm/composer baru; tidak ada library graph baru.
- Tanpa komentar kode baru; nama konstanta `SCREAMING_SNAKE_CASE`.
- Semua teks antarmuka baru berbahasa Indonesia.
- Verifikasi: `php tests\run.php`, `php -l`, `git diff --check`.
- Jangan commit sampai diminta.

## Review Focus

- `decision_tree` kosong → SVG menampilkan empty state tanpa error → `DecisionTreeGraphTest::testEmptyRowsRenderEmptyState`.
- Dua rule memakai jalur gejala yang sama → node gejala dipakai ulang, tidak dobel → `DecisionTreeGraphTest::testReusesSymptomNodesAcrossRules`.
- Rule dengan `parent` kosong → daun langsung terhubung ke root → `DecisionTreeGraphTest::testEmptyParentAttachesLeafDirectlyToRoot`.
- Tamu mengirim POST tanpa gejala → error summary, tidak ada tulisan ke `hasil` → `GuestDiagnosisTest::testProsesRejectsEmptySelectionWithoutSaving`.
- User login memakai form tamu → tidak ada penyimpanan otomatis; hanya POST `user/diagnosis` eksplisit → `GuestDiagnosisTest::testSaveEndpointRequiresExplicitPostAndLogin`.

---

### Task 1: Service `DecisionTreeGraph`

**Files:**
- Create: `app/Services/DecisionTreeGraph.php`
- Test: `tests/DecisionTreeGraphTest.php`

**Interfaces:**
- Consumes: baris `decision_tree` dengan keys `akar`, `parent`, `penyakit`, `keputusan`, `detail`; peta label `id_gejala => label`.
- Produces: `DecisionTreeGraph::__construct(array $rows, array $symptomLabels = [])`, `nodes(): array`, `edges(): array`, `toSvg(): string`, `toAccessibleList(): string`.
- Setiap node: `['id' => 'n1', 'label' => string, 'kind' => 'root'|'symptom'|'disease', 'x' => int, 'y' => int]`; setiap edge: `['from' => string, 'to' => string]`. Id node `n1`, `n2`, ... sesuai urutan pembuatan.

- [x] **Step 1: Write the failing test**

Buat `tests/DecisionTreeGraphTest.php` dengan method:

```php
public function testGroupsRowsByAkarIntoMultipleRoots(): void
{
    $graph = new App\Services\DecisionTreeGraph([
        ['akar' => '1', 'parent' => '2 3', 'penyakit' => 'Coccidiosis'],
        ['akar' => '4', 'parent' => '5', 'penyakit' => 'Snuffles'],
    ], [1 => 'Makan', 2 => 'Diare', 3 => 'Lemah', 4 => 'Napas', 5 => 'Bersin']);

    $roots = array_values(array_filter($graph->nodes(), static fn ($n): bool => $n['kind'] === 'root'));

    expectSame(2, count($roots), 'Tiap nilai akar harus menjadi root tersendiri.');
    expectSame('Makan', $roots[0]['label'], 'Label root harus memakai peta gejala.');
}

public function testReusesSymptomNodesAcrossRules(): void
{
    $graph = new App\Services\DecisionTreeGraph([
        ['akar' => '1', 'parent' => '2', 'penyakit' => 'Coccidiosis'],
        ['akar' => '1', 'parent' => '2 3', 'penyakit' => 'Snuffles'],
    ]);

    expectSame(2, count(array_filter($graph->nodes(), static fn ($n): bool => $n['kind'] === 'symptom')), 'Node gejala yang sama harus dipakai ulang.');
    expectSame(2, count(array_filter($graph->nodes(), static fn ($n): bool => $n['kind'] === 'disease')), 'Tiap rule harus punya daun penyakit.');
}

public function testEmptyParentAttachesLeafDirectlyToRoot(): void
{
    $graph = new App\Services\DecisionTreeGraph([
        ['akar' => '1', 'parent' => '', 'penyakit' => 'Coccidiosis'],
    ]);

    expectSame(2, count($graph->nodes()), 'Rule tanpa gejala harus punya root dan daun.');
    expectSame(1, count($graph->edges()), 'Root harus langsung terhubung ke daun.');
}

public function testRendersFocusableSvgWithTitleAndAccessibleList(): void
{
    $graph = new App\Services\DecisionTreeGraph([
        ['akar' => '1', 'parent' => '2', 'penyakit' => 'Coccidiosis & Vermin'],
    ]);

    $svg = $graph->toSvg();
    $list = $graph->toAccessibleList();

    expectTrue(strpos($svg, '<svg') !== false, 'Harus merender svg inline.');
    expectTrue(strpos($svg, '<path') !== false, 'Harus merender edge sebagai path.');
    expectTrue(strpos($svg, '<g tabindex="0"') !== false, 'Node harus bisa difokus.');
    expectTrue(strpos($svg, '<title>') !== false, 'Tiap node harus punya title.');
    expectTrue(strpos($svg, 'Coccidiosis &amp; Vermin') !== false, 'Label harus di-escape.');
    expectTrue(strpos($list, '<ul>') !== false && strpos($list, 'sr-only') !== false, 'Harus ada fallback daftar untuk pembaca layar.');
}

public function testEmptyRowsRenderEmptyState(): void
{
    $graph = new App\Services\DecisionTreeGraph([]);

    expectSame([], $graph->nodes(), 'Tidak ada node untuk rule kosong.');
    expectSame([], $graph->edges(), 'Tidak ada edge untuk rule kosong.');
    expectTrue(strpos($graph->toSvg(), 'Tidak ada pohon') !== false, 'SVG kosong harus punya pesan.');
    expectSame('', $graph->toAccessibleList(), 'Daftar kosong tidak perlu output.');
}
```

- [x] **Step 2: Run test to verify it fails**

Run: `php tests\run.php`
Expected: FAIL di `DecisionTreeGraphTest` karena class belum ada.

- [x] **Step 3: Implement `DecisionTreeGraph`**

Buat class final di namespace `App\Services`. Aturan tetap:
- Root per `(string) $row['akar']`, fallback `'Umum'` bila kosong; label root dari `$symptomLabels[(string) $row['akar']]` atau nilai akar itu sendiri.
- Token jalur dari `preg_split('/\s+/', trim((string) $row['parent']))`; node `symptom` dishared per pasangan root+token; label dari `$symptomLabels[$token]` atau token.
- Daun `disease` dibuat per rule dengan label `(string) $row['penyakit']`.
- Layout deterministik: depth root `0`, token ke-`i` depth `i+1`, daun depth `count(tokens)+1`; `x = 40 + index * 180` di dalam depth masing-masing, `y = 40 + depth * 110`.
- `toSvg()`: `<svg class="decision-tree-svg" viewBox="0 0 {width} {height}" role="img" aria-label="Pohon keputusan">`, edge sebagai `<path class="tree-edge" d="M ... C ..."/>`, node sebagai `<g class="tree-node" tabindex="0"><title>...</title><rect width="150" height="44"/><text>...</text></g>`. Width/height dari x/y maksimum ditambah margin node. Escape semua teks dengan `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')`. Rule kosong menghasilkan `<text>Tidak ada pohon. Jalankan Proses Mining.</text>`.
- `toAccessibleList()`: `<div class="sr-only"><ul>...</ul></div>` berisi root → token → daun bersarang; kembalikan `''` bila tidak ada node.

- [x] **Step 4: Run test to verify it passes**

Run: `php tests\run.php`
Expected: PASS semua `DecisionTreeGraphTest`, tidak ada regresi test lain.

### Task 2: Render SVG di halaman admin

**Files:**
- Modify: `app/Controllers/Admin.php:431-487`, `app/Controllers/Admin.php:680-708`
- Modify: `app/Views/admin/decisiontree.php`
- Modify: `public/css/app.css`
- Test: `tests/DecisionTreeViewTest.php`

**Interfaces:**
- Consumes: `DecisionTreeGraph::__construct(array $rows, array $symptomLabels = [])`, `toSvg(): string`, `toAccessibleList(): string`.
- Produces: data view `svg` dan `treeList` (string) untuk `admin/decisiontree`.

- [x] **Step 1: Write the failing test**

Buat `tests/DecisionTreeViewTest.php`:

```php
public function testAdminControllerUsesDecisionTreeGraph(): void
{
    $controller = (string) file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');

    expectTrue(strpos($controller, 'new DecisionTreeGraph(') !== false, 'Admin harus memakai DecisionTreeGraph.');
    expectTrue(strpos($controller, "'svg' =>") !== false, 'Admin harus mengirim svg ke view.');
    expectTrue(strpos($controller, "'treeList' =>") !== false, 'Admin harus mengirim daftar aksesibel ke view.');
    expectTrue(strpos($controller, 'function buildDecisionTreeHtml') === false, 'Builder HTML lama harus dihapus.');
    expectTrue(strpos($controller, 'function renderTreeNode') === false, 'Renderer list lama harus dihapus.');
}

public function testDecisionTreeViewUsesSvgAndCollapsesRawRules(): void
{
    $view = (string) file_get_contents(APP_ROOT . '/app/Views/admin/decisiontree.php');
    $css = (string) file_get_contents(APP_ROOT . '/public/css/app.css');

    expectTrue(strpos($view, '<?= $svg; ?>') !== false, 'View harus merender svg.');
    expectTrue(strpos($view, '<?= $treeList; ?>') !== false, 'View harus merender fallback aksesibel.');
    expectTrue(strpos($view, '<details') !== false, 'Tabel aturan harus berada di dalam details.');
    expectTrue(strpos($view, 'Lihat aturan mentah') !== false, 'Ringkasan details harus berbahasa Indonesia.');
    expectTrue(strpos($view, '<table') !== false, 'Tabel tetap dipertahankan sebagai fallback.');
    expectTrue(strpos($css, '.decision-tree-svg') !== false, 'CSS svg harus tersedia.');
}
```

- [x] **Step 2: Run test to verify it fails**

Run: `php tests\run.php`
Expected: FAIL di `DecisionTreeViewTest`.

- [x] **Step 3: Implementasi render SVG**

Tambah `use App\Services\DecisionTreeGraph;` di `Admin.php`. Di `decisionTree()`, query rows menyertakan `dt.akar`, susun peta `id_gejala => trim(kategori . ' ' . gejala)` dari `data_gejala`, lalu isi `'svg' => $graph->toSvg()` dan `'treeList' => $graph->toAccessibleList()`. Hapus `buildDecisionTreeHtml()` dan `renderTreeNode()`. Di view, ganti isi kartu “Visualisasi Pohon” dengan `<?= $svg; ?>` dan `<?= $treeList; ?>`; bungkus kartu tabel aturan dengan `<details class="mb-4"><summary>Lihat aturan mentah</summary>...</details>`. Tambah aturan CSS `.decision-tree-svg` (lebar penuh, tinggi otomatis) dan `.tree-edge`/`.tree-node` memakai warna token SB Admin `#4e73df`, `#e3e6f0`, `#f8f9fc`.

- [x] **Step 4: Run test to verify it passes**

Run: `php tests\run.php`
Expected: PASS semua test.

### Task 3: Controller publik dan endpoint simpan eksplisit

**Files:**
- Create: `app/Controllers/Diagnosis.php`
- Modify: `app/Config/Routes.php`
- Modify: `app/Controllers/Home.php`
- Modify: `app/Controllers/User.php`
- Modify: `tests/LandingPageTest.php`
- Test: `tests/GuestDiagnosisTest.php`

**Interfaces:**
- Consumes: `DiagnosisRuleMatcher::match(array $selectedGejala, array $rules): ?array`, `penyakitModel::hasil($id)`, `decisionTreeModel::saveResult($id_user, $id_penyakit, $penyakit)`.
- Produces: `Diagnosis::index()`, `Diagnosis::proses()`, `User::simpanDiagnosis()`; data view `gejala`, `errors`, `hasil`, `gejalaDetail`.

- [x] **Step 1: Write the failing test**

Buat `tests/GuestDiagnosisTest.php`:

```php
public function testDiagnosisRoutesArePublic(): void
{
    $routes = (string) file_get_contents(APP_ROOT . '/app/Config/Routes.php');

    expectTrue(strpos($routes, "get('diagnosa', 'Diagnosis::index')") !== false, 'Route form tamu harus ada.');
    expectTrue(strpos($routes, "post('diagnosa/proses', 'Diagnosis::proses')") !== false, 'Route proses tamu harus POST.');
    expectTrue(strpos($routes, "post('diagnosis', 'User::simpanDiagnosis')") !== false, 'Simpan riwayat harus POST eksplisit.');
    expectTrue(
        strpos($routes, "get('diagnosa'") < strpos($routes, "group('user', ['filter' => 'login']"),
        'Route tamu harus didefinisikan sebelum group login.'
    );
}

public function testProsesRejectsEmptySelectionWithoutSaving(): void
{
    $controller = (string) file_get_contents(APP_ROOT . '/app/Controllers/Diagnosis.php');

    expectTrue(strpos($controller, "Harus memilih gejala minimal 1") !== false, 'Harus ada pesan gejala kosong.');
    expectTrue(strpos($controller, 'saveResult') === false, 'Controller tamu tidak boleh menulis hasil.');
    expectTrue(strpos($controller, 'new DiagnosisRuleMatcher()') !== false, 'Controller tamu harus memakai matcher.');
    expectTrue(strpos($controller, 'penyakitModel') !== false, 'Controller tamu harus memakai penyakitModel.');
}

public function testProsesRejectsUnknownDiseaseWithoutSaving(): void
{
    $controller = (string) file_get_contents(APP_ROOT . '/app/Controllers/Diagnosis.php');

    expectTrue(strpos($controller, 'Penyakit untuk rule ini tidak ditemukan') !== false, 'Harus ada pesan penyakit tidak ditemukan.');
    expectTrue(strpos($controller, 'findAll()') !== false, 'Rule harus diambil dari decisionTreeModel.');
}

public function testSaveEndpointRequiresExplicitPostAndLogin(): void
{
    $controller = (string) file_get_contents(APP_ROOT . '/app/Controllers/User.php');

    expectTrue(strpos($controller, 'function simpanDiagnosis') !== false, 'Endpoint simpan harus ada.');
    expectTrue(strpos($controller, 'user() === null') !== false, 'Simpan harus menolak tamu.');
    expectTrue(strpos($controller, "getPost('id_penyakit')") !== false, 'Simpan hanya menerima id penyakit.');
    expectTrue(strpos($controller, 'saveResult(') !== false, 'Simpan harus memanggil saveResult.');
}
```

Update `tests/LandingPageTest.php::testDiagnosisCtaStartsConsultationAfterLogin`: ganti assertion `user/diagnosis` di `Home.php` dengan `site_url('diagnosa')`, dan ganti nama method menjadi `testDiagnosisCtaStartsGuestConsultation`.

- [x] **Step 2: Run test to verify it fails**

Run: `php tests\run.php`
Expected: FAIL di `GuestDiagnosisTest` dan `LandingPageTest`.

- [x] **Step 3: Implementasi controller dan rute**

Buat `Diagnosis` extends `BaseController` dengan constructor membuat `gejalaModel`, `decisionTreeModel`, `penyakitModel`.
`index()`: kirim `gejala = $this->gejalaModel->orderBy('kategori')->findAll()`, `errors = []`, render `diagnosis/index`.
`proses()`: baca `getPost('gejala')`, normalisasi ke array; bila kosong kembalikan view `diagnosis/index` dengan `gejala` dari `orderBy('kategori')->findAll()` dan `errors = ['Harus memilih gejala minimal 1']`. Lakukan `match()` terhadap `$this->decisionTreeModel->findAll()`; bila `null`, error `Gejala masih belum sesuai, harap pilih gejala yang sesuai!`; bila `penyakitModel->hasil((int) $rule['keputusan'])` mengembalikan `null`, error `Penyakit untuk rule ini tidak ditemukan`. Semua cabang error mengirim ulang `gejala` dan `errors` ke view yang sama. Sukses: kirim `hasil` (objek penyakit), `gejalaDetail` dari `$rule['detail']`, `errors = []`, render `diagnosis/hasil`. Jangan pernah memanggil `saveResult()` di controller ini.
Di `Routes.php`, daftar `get('diagnosa', 'Diagnosis::index')` dan `post('diagnosa/proses', 'Diagnosis::proses')` di luar group login; di group `user` tambah `post('diagnosis', 'User::simpanDiagnosis')`.
`Home::startDiagnosis()`: ganti redirect_url lama dengan `return redirect()->to(site_url('diagnosa'));`.
`User::simpanDiagnosis()`: tolak bila `user() === null`; baca `id_penyakit`, pastikan > 0, ambil penyakit lewat `penyakitModel->hasil()`, kembalikan redirect ke `user/prosesdiagnosis` dengan flash `diagnosis` bila tidak valid; jika valid panggil `saveResult((int) user()->id, $id, $penyakit->penyakit)` lalu redirect ke `user/index` dengan flash `pesan = 'Riwayat diagnosis tersimpan'`.

- [x] **Step 4: Run test to verify it passes**

Run: `php tests\run.php`
Expected: PASS semua test.

### Task 4: View tamu dan penyesuaian CTA

**Files:**
- Create: `app/Views/diagnosis/index.php`
- Create: `app/Views/diagnosis/hasil.php`
- Modify: `app/Views/landing.php`
- Modify: `app/Views/templates/public.php`
- Test: `tests/GuestDiagnosisViewTest.php`

**Interfaces:**
- Consumes: data view `gejala`, `errors`, `hasil`, `gejalaDetail`.
- Produces: tampilan form tamu, kartu hasil, CTA login tamu, dan form simpan riwayat untuk user login.

- [x] **Step 1: Write the failing test**

Buat `tests/GuestDiagnosisViewTest.php`:

```php
public function testGuestFormUsesCsrfAndUniqueCheckboxIds(): void
{
    $view = (string) file_get_contents(APP_ROOT . '/app/Views/diagnosis/index.php');

    expectTrue(strpos($view, "extend('templates/public')") !== false, 'Form tamu harus memakai layout publik.');
    expectTrue(strpos($view, 'csrf_field()') !== false, 'Form tamu harus punya CSRF.');
    expectTrue(strpos($view, 'id="gejala-<?= $g[\'id_gejala\']; ?>"') !== false, 'Checkbox harus punya id unik.');
    expectTrue(strpos($view, 'for="gejala-<?= $g[\'id_gejala\']; ?>"') !== false, 'Label harus mengarah ke checkbox yang sama.');
    expectTrue(strpos($view, 'id="diagnosis-errors"') !== false, 'Harus ada error summary.');
    expectTrue(strpos($view, "base_url('diagnosa/proses')") !== false, 'Form harus POST ke endpoint tamu.');
}

public function testResultPageSeparatesGuestAndMemberActions(): void
{
    $view = (string) file_get_contents(APP_ROOT . '/app/Views/diagnosis/hasil.php');

    expectTrue(strpos($view, 'user() === null') !== false, 'CTA login harus khusus tamu.');
    expectTrue(strpos($view, "base_url('login')") !== false, 'Tamu harus diarahkan ke login.');
    expectTrue(strpos($view, "base_url('user/diagnosis')") !== false, 'Simpan riwayat harus POST ke user/diagnosis.');
    expectTrue(strpos($view, 'csrf_field()') !== false, 'Form simpan harus punya CSRF.');
    expectTrue(strpos($view, 'Simpan riwayat') !== false, 'Tombol simpan harus eksplisit.');
    expectTrue(strpos($view, 'saveResult') === false, 'View tidak boleh menyimpan langsung.');
}

public function testPublicCtaPointsToGuestDiagnosis(): void
{
    foreach (['app/Views/landing.php', 'app/Views/templates/public.php'] as $path) {
        $view = (string) file_get_contents(APP_ROOT . '/' . $path);
        expectTrue(strpos($view, "base_url('mulai-diagnosis')") !== false, $path . ': CTA harus tetap memakai route mulai-diagnosis.');
    }

    $home = (string) file_get_contents(APP_ROOT . '/app/Controllers/Home.php');
    expectTrue(strpos($home, "site_url('diagnosa')") !== false, 'CTA harus mengarah ke /diagnosa.');
}
```

- [x] **Step 2: Run test to verify it fails**

Run: `php tests\run.php`
Expected: FAIL karena view tamu belum ada.

- [x] **Step 3: Implementasi view**

Buat `diagnosis/index.php` extending `templates/public`: judul “Konsultasi Diagnosis”, form `method="post" action="<?= base_url('diagnosa/proses'); ?>"`, `csrf_field()`, error summary `id="diagnosis-errors" role="alert"` yang hanya tampil bila `$errors` tidak kosong, checkbox gejala dengan `id`/`for` `gejala-<?= $g['id_gejala']; ?>`, tombol “Lihat Hasil Diagnosis”.
Buat `diagnosis/hasil.php` extending `templates/public`: kartu hasil menampilkan `$hasil->penyakit`, `$gejalaDetail`, `$hasil->solusi`, `$hasil->obat`; tautan “Konsultasi lagi” ke `base_url('diagnosa')`; bila `user() === null`, tampilkan CTA login `base_url('login')`; jika tidak, tampilkan form POST `base_url('user/diagnosis')` dengan `csrf_field()`, hidden `id_penyakit`, dan tombol “Simpan riwayat”.
Pastikan `landing.php` dan `templates/public.php` tetap memakai `base_url('mulai-diagnosis')` sehingga redirect `Home::startDiagnosis` yang sudah diubah menuju `/diagnosa`.

- [x] **Step 4: Run test to verify it passes**

Run: `php tests\run.php`
Expected: PASS semua test termasuk `UiUsabilityTest`.

### Task 5: Verifikasi akhir

**Files:** none

- [x] **Step 1: Run full suite**

Run: `php tests\run.php`
Expected: 0 failures.

- [x] **Step 2: Lint semua file PHP yang berubah**

Run: `php -l app/Services/DecisionTreeGraph.php`, `php -l app/Controllers/Diagnosis.php`, `php -l app/Controllers/Admin.php`, `php -l app/Controllers/Home.php`, `php -l app/Controllers/User.php`, `php -l app/Config/Routes.php`
Expected: `No syntax errors detected` untuk semuanya.

- [x] **Step 3: Check whitespace**

Run: `git diff --check`
Expected: tanpa output.

- [x] **Step 4: Catat hasil Stage 3**

Perbarui checkbox di file rencana ini sesuai task yang selesai. Jangan commit.
