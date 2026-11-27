<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Artikel edukasi kesehatan dan perawatan kelinci berbasis algoritma C4.5.">
    <title><?= esc($title); ?> — Diagnosis Kelinci</title>
    <link href="<?= base_url('vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="<?= base_url('css/sb-admin-2.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('css/app.css'); ?>" rel="stylesheet">
</head>
<body class="bg-light">
    <header class="site-header bg-white border-bottom sticky-top shadow-sm">
        <div class="container py-2 d-flex align-items-center justify-content-between">
            <a class="brand d-flex align-items-center font-weight-bold text-primary text-decoration-none" href="<?= base_url('/'); ?>">
                <img src="<?= base_url('img/rabbit.svg'); ?>" alt="" class="mr-2" width="30" height="30">
                <span>Diagnosis Kelinci</span>
            </a>
            <nav class="d-flex align-items-center">
                <a class="nav-link text-dark font-weight-bold d-none d-md-inline-block mr-2" href="<?= base_url('/'); ?>">Beranda</a>
                <a class="nav-link text-primary font-weight-bold d-none d-md-inline-block mr-2" href="<?= base_url('artikel'); ?>">Artikel</a>
                <a class="btn btn-primary btn-sm mr-2" href="<?= base_url('mulai-diagnosis'); ?>">
                    <i class="fas fa-stethoscope mr-1"></i> Mulai Diagnosis
                </a>
                <?php if (function_exists('logged_in') && logged_in()) : ?>
                    <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('user'); ?>">
                        <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                    </a>
                <?php else : ?>
                    <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('login'); ?>">Masuk</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container py-4 py-md-5">
        <?= $this->renderSection('page-content'); ?>
    </main>

    <footer class="dk-footer-complex">
        <div class="dk-footer-inner">
            <div class="dk-footer-brand">
                <div class="d-flex align-items-center mb-2">
                    <img src="<?= base_url('img/rabbit.svg'); ?>" alt="" style="width: 28px; height: 28px;" class="mr-2">
                    <h4 class="mb-0">Diagnosis Kelinci</h4>
                </div>
                <p>Sistem pakar pendukung keputusan diagnosis penyakit kelinci menggunakan algoritma klasifikasi pohon keputusan C4.5.</p>
            </div>
            <div class="dk-footer-col">
                <h5>Navigasi</h5>
                <ul>
                    <li><a href="<?= base_url('/'); ?>">Beranda</a></li>
                    <li><a href="<?= base_url('artikel'); ?>">Artikel & Panduan</a></li>
                    <li><a href="<?= base_url('mulai-diagnosis'); ?>">Konsultasi Diagnosis</a></li>
                </ul>
            </div>
            <div class="dk-footer-col">
                <h5>Kategori Panduan</h5>
                <ul>
                    <li><a href="<?= base_url('artikel?kategori=perawatan'); ?>">Perawatan Kelinci</a></li>
                    <li><a href="<?= base_url('artikel?kategori=penyakit'); ?>">Gejala & Penyakit</a></li>
                    <li><a href="<?= base_url('artikel?kategori=nutrisi'); ?>">Nutrisi Sehat</a></li>
                    <li><a href="<?= base_url('artikel?kategori=pencegahan'); ?>">Pencegahan & Vaksin</a></li>
                </ul>
            </div>
        </div>
        <div class="dk-footer-bottom">
            <span>&copy; <?= date('Y'); ?> Diagnosis Kelinci C4.5 · Seluruh hak cipta dilindungi.</span>
            <span class="text-muted small">Catatan: Rekomendasi sistem merupakan skrining pendukung, bukan pengganti pemeriksaan dokter hewan.</span>
        </div>
    </footer>

    <script src="<?= base_url('vendor/jquery/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
</body>
</html>
