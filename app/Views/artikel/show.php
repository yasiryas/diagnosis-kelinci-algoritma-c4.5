<?= $this->extend('templates/public'); ?>

<?= $this->section('page-content'); ?>
<div class="row">
    <div class="col-lg-8 mx-auto">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb bg-transparent px-0 py-1 small">
                <li class="breadcrumb-item"><a href="<?= base_url('/'); ?>">Beranda</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('artikel'); ?>">Artikel</a></li>
                <?php if ($item !== null) : ?>
                    <li class="breadcrumb-item active text-truncate" style="max-width: 250px;" aria-current="page"><?= esc($item['judul']); ?></li>
                <?php endif; ?>
            </ol>
        </nav>

        <?php if ($item === null) : ?>
            <div class="card shadow-sm border-0 py-5">
                <div class="card-body text-center text-muted">
                    <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                    <h4>Artikel Tidak Ditemukan</h4>
                    <p class="text-muted">Artikel yang Anda cari mungkin telah dihapus atau tautan tidak valid.</p>
                    <a class="btn btn-primary mt-2" href="<?= base_url('artikel'); ?>">Kembali ke Daftar Artikel</a>
                </div>
            </div>
        <?php else : ?>
            <article class="card shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
                <?php if (!empty($item['gambar'])) : ?>
                    <div style="max-height: 400px; overflow: hidden; background: #eef2f7;">
                        <img src="<?= esc($item['gambar'], 'attr'); ?>" alt="<?= esc($item['judul'], 'attr'); ?>" style="width: 100%; height: auto; object-fit: cover;" referrerpolicy="no-referrer">
                    </div>
                <?php endif; ?>
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge badge-primary px-3 py-1 font-weight-normal mr-2">
                            <?= esc($item['nama_kategori'] ?? 'Panduan'); ?>
                        </span>
                        <?php if (!empty($item['published_at'])) : ?>
                            <span class="text-muted small">
                                <i class="far fa-calendar-alt mr-1"></i><?= date('d F Y', strtotime($item['published_at'])); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <h1 class="h2 font-weight-bold text-gray-900 mb-3"><?= esc($item['judul']); ?></h1>
                    <div class="lead text-muted p-3 mb-4 border-left border-primary" style="background: #f8f9fc; border-left-width: 4px !important; border-radius: 4px;">
                        <?= esc($item['ringkasan']); ?>
                    </div>

                    <div class="article-content text-gray-800" style="font-size: 1.05rem; line-height: 1.8;">
                        <?= nl2br(esc($item['konten'])); ?>
                    </div>

                    <!-- CTA Box Di Bawah Artikel -->
                    <div class="card border-0 mt-5 p-4" style="background: linear-gradient(135deg, #e8f0fe 0%, #f1f3f9 100%); border-radius: 10px;">
                        <div class="d-md-flex align-items-center justify-content-between">
                            <div class="mb-3 mb-md-0 pr-md-3">
                                <h5 class="font-weight-bold text-gray-900 mb-1">
                                    <i class="fas fa-stethoscope text-primary mr-1"></i> Khawatir dengan kondisi kelinci Anda?
                                </h5>
                                <p class="small text-muted mb-0">Gunakan sistem diagnosis C4.5 kami untuk deteksi gejala klinis secara cepat dan gratis.</p>
                            </div>
                            <a href="<?= base_url('mulai-diagnosis'); ?>" class="btn btn-primary btn-sm px-4 py-2 text-nowrap font-weight-bold">
                                Mulai Diagnosis
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection(); ?>
