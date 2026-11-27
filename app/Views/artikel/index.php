<?= $this->extend('templates/public'); ?>

<?= $this->section('page-content'); ?>
<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="d-md-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Artikel & Panduan Kelinci</h1>
                <p class="text-muted mb-0">Edukasi perawatan harian, nutrisi sehat, dan pencegahan penyakit kelinci.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <form action="<?= base_url('artikel'); ?>" method="GET" class="form-inline">
                    <?php if (!empty($currentCategory)) : ?>
                        <input type="hidden" name="kategori" value="<?= esc($currentCategory, 'attr'); ?>">
                    <?php endif; ?>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" placeholder="Cari artikel..." name="keyword" value="<?= esc($keyword ?? ''); ?>">
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-sm" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Filter Kategori -->
        <?php if (!empty($kategoriList)) : ?>
            <div class="mb-4 d-flex flex-wrap align-items-center" style="gap: 0.5rem;">
                <span class="small font-weight-bold text-muted mr-1">Kategori:</span>
                <a href="<?= base_url('artikel' . (!empty($keyword) ? '?keyword=' . urlencode($keyword) : '')); ?>"
                   class="btn btn-sm <?= empty($currentCategory) ? 'btn-primary' : 'btn-outline-primary'; ?> rounded-pill px-3">
                    Semua
                </a>
                <?php foreach ($kategoriList as $kat) : ?>
                    <a href="<?= base_url('artikel?kategori=' . esc($kat['slug'], 'attr') . (!empty($keyword) ? '&keyword=' . urlencode($keyword) : '')); ?>"
                       class="btn btn-sm <?= ($currentCategory === $kat['slug']) ? 'btn-primary' : 'btn-outline-primary'; ?> rounded-pill px-3">
                        <?= esc($kat['nama']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($artikel)) : ?>
            <div class="card shadow-sm border-0 py-5 my-4">
                <div class="card-body text-center text-muted">
                    <i class="fas fa-search fa-3x text-gray-300 mb-3 d-block"></i>
                    <h5>Tidak ada artikel yang ditemukan</h5>
                    <p class="text-muted small">Coba kata kunci lain atau pilih kategori yang berbeda.</p>
                    <a href="<?= base_url('artikel'); ?>" class="btn btn-outline-primary btn-sm mt-2">
                        Reset Filter
                    </a>
                </div>
            </div>
        <?php else : ?>
            <div class="row">
                <?php foreach ($artikel as $item) : ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <article class="card shadow-sm h-100 border-0 dk-article-card" style="border-radius: 10px; overflow: hidden; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                            <?php if (!empty($item['gambar'])) : ?>
                                <div style="height: 180px; overflow: hidden; background: #eef2f7;">
                                    <img src="<?= esc($item['gambar'], 'attr'); ?>" alt="<?= esc($item['judul'], 'attr'); ?>" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy" referrerpolicy="no-referrer">
                                </div>
                            <?php else : ?>
                                <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="height: 180px;">
                                    <i class="fas fa-image fa-2x text-gray-300"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge badge-primary px-2 py-1" style="font-size: 0.75rem;">
                                        <?= esc($item['nama_kategori'] ?? 'Panduan'); ?>
                                    </span>
                                    <small class="text-muted">
                                        <?= !empty($item['published_at']) ? date('d M Y', strtotime($item['published_at'])) : ''; ?>
                                    </small>
                                </div>
                                <h2 class="h5 card-title font-weight-bold mb-2">
                                    <a href="<?= base_url('artikel/' . esc($item['slug'], 'attr')); ?>" class="text-dark text-decoration-none">
                                        <?= esc($item['judul']); ?>
                                    </a>
                                </h2>
                                <p class="card-text text-muted small flex-grow-1" style="line-height: 1.5;">
                                    <?= esc($item['ringkasan']); ?>
                                </p>
                                <div class="mt-3 pt-2 border-top">
                                    <a class="font-weight-bold text-primary small text-decoration-none" href="<?= base_url('artikel/' . esc($item['slug'], 'attr')); ?>">
                                        Baca artikel <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="mt-4 pt-3 border-top">
            <a class="btn btn-link pl-0 text-muted" href="<?= base_url('/'); ?>">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
