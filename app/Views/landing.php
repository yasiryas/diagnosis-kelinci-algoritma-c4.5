<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem pendukung keputusan diagnosis penyakit kelinci berbasis algoritma decision tree C4.5.">
    <title>Diagnosis Kelinci — C4.5</title>
    <link href="<?= base_url('vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="<?= base_url('css/sb-admin-2.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('css/app.css'); ?>" rel="stylesheet">
</head>

<body>
    <a class="skip-link" href="#main">Lewati ke konten</a>

    <header class="sticky-top dk-public-header">
        <a class="dk-public-brand" href="<?= base_url('/'); ?>">
            <span class="dk-public-brand-mark">
                <img src="<?= base_url('img/rabbit.svg'); ?>" alt="" aria-hidden="true">
            </span>
            <span>Diagnosis Kelinci</span>
        </a>
        <nav class="dk-public-nav" aria-label="Navigasi utama">
            <a href="#keunggulan">Keunggulan</a>
            <a href="#cara-kerja">Cara kerja</a>
            <a href="#artikel-terbaru">Artikel</a>
            <a class="dk-btn-outline" href="<?= base_url('login'); ?>">Masuk</a>
        </nav>
    </header>

    <main id="main" tabindex="-1">
        <!-- Hero Section -->
        <section class="dk-hero">
            <div class="dk-hero-copy">
                <p class="eyebrow"><i class="fas fa-brain mr-1"></i> Sistem pendukung keputusan</p>
                <h1>Kenali penyakit kelinci lebih cepat & akurat.</h1>
                <p class="lead">Pilih gejala klinis yang terlihat pada kelinci. Algoritma pohon keputusan C4.5 membantu menganalisis kemungkinan penyakit serta tindakan solusi awal.</p>
                <div class="d-flex flex-wrap align-items-center mb-3" style="gap: 1rem;">
                    <a class="dk-btn-primary" href="<?= base_url('mulai-diagnosis'); ?>">
                        Mulai diagnosis<i class="fas fa-arrow-right ml-2" aria-hidden="true"></i>
                    </a>
                    <a class="font-weight-bold text-dark ml-2" href="#cara-kerja">
                        Lihat cara kerja <i class="fas fa-chevron-down ml-1"></i>
                    </a>
                </div>
                <p class="note mb-0 text-muted small">
                    <i class="fas fa-info-circle mr-1"></i> Untuk peternak dan praktisi pemelihara kelinci. Bukan pengganti pemeriksaan dokter hewan.
                </p>
            </div>

            <div class="dk-hero-visual">
                <div class="card dk-hero-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="font-weight-bold"><i class="fas fa-project-diagram mr-1"></i> Alur konsultasi</span>
                        <span class="badge badge-light px-2 py-1 font-weight-bold">C4.5</span>
                    </div>
                    <div class="card-body">
                        <img class="dk-hero-rabbit" src="<?= base_url('img/rabbit.svg'); ?>" alt="" aria-hidden="true">
                        <div class="mt-4 pt-3 border-top">
                            <p class="mb-2"><strong>Gejala</strong> — pilih tanda klinis yang dialami kelinci.</p>
                            <p class="mb-2"><strong>C4.5</strong> — aturan pohon keputusan menilai kombinasi gejala.</p>
                            <p class="mb-0"><strong>Hasil</strong> — kemungkinan penyakit beserta rekomendasi langkah lanjutan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Highlights Strip -->
        <section class="dk-highlights-bar">
            <div class="dk-highlight-item">
                <div class="dk-highlight-icon">
                    <i class="fas fa-tree"></i>
                </div>
                <div class="dk-highlight-text">
                    <strong>Algoritma C4.5</strong>
                    <span>Pohon keputusan matematis</span>
                </div>
            </div>
            <div class="dk-highlight-item">
                <div class="dk-highlight-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="dk-highlight-text">
                    <strong>Deteksi Cepat</strong>
                    <span>Hasil keluar dalam hitungan detik</span>
                </div>
            </div>
            <div class="dk-highlight-item">
                <div class="dk-highlight-icon">
                    <i class="fas fa-capsules"></i>
                </div>
                <div class="dk-highlight-text">
                    <strong>Solusi & Obat</strong>
                    <span>Panduan pertolongan pertama</span>
                </div>
            </div>
            <div class="dk-highlight-item">
                <div class="dk-highlight-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="dk-highlight-text">
                    <strong>Artikel Edukatif</strong>
                    <span>Tips nutrisi & perawatan kelinci</span>
                </div>
            </div>
        </section>

        <!-- Keunggulan Section -->
        <section class="dk-section-grid" id="keunggulan">
            <h2>Mengapa menggunakan sistem ini?</h2>
            <article class="dk-info-card card">
                <div class="text-primary mb-2" style="font-size: 1.5rem;">
                    <i class="fas fa-calculator"></i>
                </div>
                <h3>Kalkulasi C4.5 yang Teruji</h3>
                <p>Menggunakan perhitungan Entropy dan Information Gain yang ditransformasikan menjadi pohon keputusan terstruktur untuk akurasi klasifikasi penyakit.</p>
            </article>

            <article class="dk-info-card card">
                <div class="text-primary mb-2" style="font-size: 1.5rem;">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Akses Mudah Kapan Saja</h3>
                <p>Dapat dibuka langsung dari ponsel atau komputer tanpa instalasi rumit. Sangat praktis saat Anda berada di kandang dan perlu diagnosis cepat.</p>
            </article>

            <article class="dk-info-card card">
                <div class="text-primary mb-2" style="font-size: 1.5rem;">
                    <i class="fas fa-shield-virus"></i>
                </div>
                <h3>Solusi Medis & Pencegahan</h3>
                <p>Tidak hanya memberikan nama penyakit, sistem juga menyertakan saran obat awal dan tindakan solusi agar kelinci segera tertolong.</p>
            </article>
        </section>

        <!-- Cara Kerja Section -->
        <?php
        $steps = [
            ['01', 'Pilih gejala', 'Centang tanda yang terlihat pada kelinci, lalu lanjutkan.'],
            ['02', 'Proses aturan', 'Sistem mencocokkan pilihan dengan aturan pohon keputusan C4.5.'],
            ['03', 'Baca hasil', 'Lihat kemungkinan diagnosis dan gunakan sebagai panduan awal.'],
        ];
        ?>
        <section class="dk-section-grid" id="cara-kerja">
            <h2>Tiga langkah, tanpa ribet.</h2>
            <?php foreach ($steps as [$number, $heading, $description]) : ?>
                <article class="dk-info-card card">
                    <span class="text-dark font-weight-bold mb-2"><?= $number ?></span>
                    <h3><?= $heading ?></h3>
                    <p><?= $description ?></p>
                </article>
            <?php endforeach; ?>
        </section>

        <!-- Artikel Section -->
        <section class="dk-section-grid" id="artikel-terbaru">
            <h2>Artikel terbaru.</h2>
            <?php if (empty($artikelRingkasan)) : ?>
                <div class="col-12 text-center text-muted py-5 card">
                    <p class="mb-0">Belum ada artikel yang diterbitkan.</p>
                </div>
            <?php else : ?>
                <?php foreach ($artikelRingkasan as $item) : ?>
                    <article class="dk-info-card card p-0 overflow-hidden">
                        <?php if (!empty($item['gambar'])) : ?>
                            <div class="dk-card-media">
                                <img src="<?= esc($item['gambar'], 'attr'); ?>" alt="" class="card-img-top" loading="lazy" width="600" height="400" referrerpolicy="no-referrer">
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h3><?= esc($item['judul']); ?></h3>
                            <p><?= esc($item['ringkasan']); ?></p>
                            <a href="<?= base_url('artikel/' . esc($item['slug'], 'attr')); ?>">Baca artikel</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="dk-section-footer">
                <a class="dk-btn-outline" href="<?= base_url('artikel'); ?>">Lihat semua artikel</a>
            </div>
        </section>

        <!-- CTA Banner -->
        <section class="dk-cta-banner">
            <h2>Kelinci Anda Menunjukkan Gejala Sakit?</h2>
            <p>Jangan tunggu kondisi semakin parah. Cek gejala klinis kelinci sekarang juga untuk penanganan yang tepat dan cepat.</p>
            <a class="btn-white" href="<?= base_url('mulai-diagnosis'); ?>">
                Mulai diagnosis <i class="fas fa-stethoscope"></i>
            </a>
        </section>
    </main>

    <!-- Footer -->
    <footer class="dk-footer-complex">
        <div class="dk-footer-inner">
            <div class="dk-footer-brand">
                <h4 class="mb-2">Diagnosis Kelinci</h4>
                <p>Sistem pakar pendukung keputusan diagnosis penyakit kelinci menggunakan algoritma klasifikasi pohon keputusan C4.5. Membantu peternak dan pehobi mendeteksi gejala sejak dini.</p>
            </div>
            <div class="dk-footer-col">
                <h5>Navigasi</h5>
                <ul>
                    <li><a href="<?= base_url('/'); ?>">Beranda</a></li>
                    <li><a href="#keunggulan">Keunggulan</a></li>
                    <li><a href="#cara-kerja">Cara Kerja</a></li>
                    <li><a href="<?= base_url('artikel'); ?>">Artikel & Panduan</a></li>
                    <li><a href="<?= base_url('mulai-diagnosis'); ?>">Konsultasi Diagnosis</a></li>
                </ul>
            </div>
            <div class="dk-footer-col">
                <h5>Layanan</h5>
                <ul>
                    <li><a href="<?= base_url('login'); ?>">Masuk Sistem</a></li>
                    <li><a href="<?= base_url('register'); ?>">Daftar Akun Baru</a></li>
                    <li><a href="<?= base_url('artikel?kategori=perawatan'); ?>">Tips Perawatan</a></li>
                    <li><a href="<?= base_url('artikel?kategori=penyakit'); ?>">Ensiklopedia Penyakit</a></li>
                </ul>
            </div>
        </div>
        <div class="dk-footer-bottom">
            <span>&copy; <?= date('Y'); ?> Diagnosis Kelinci C4.5 · Seluruh hak cipta dilindungi.</span>
            <span class="text-muted small">Catatan: Rekomendasi sistem merupakan skrining pendukung, bukan pengganti pemeriksaan dokter hewan.</span>
        </div>
    </footer>
</body>

</html>
