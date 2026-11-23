<?= $this->extend('templates/public'); ?>

<?= $this->section('page-content'); ?>
<div class="row">
    <div class="col-lg-10 mx-auto">
        <h1 class="h3 mb-2 text-gray-800">Artikel</h1>
        <p class="text-muted mb-4">Panduan ringkas merawat kelinci dan mengenali penyakitnya.</p>
        <?php if (empty($artikel)) : ?>
            <div class="card shadow-sm">
                <div class="card-body text-center text-muted py-5">Belum ada artikel yang diterbitkan.</div>
            </div>
        <?php endif; ?>
        <div class="row">
            <?php foreach ($artikel as $item) : ?>
                <div class="col-md-6 mb-4">
                    <article class="card shadow-sm h-100">
                        <div class="card-body">
                            <p class="small text-primary mb-2"><?= esc($item['nama_kategori'] ?? ''); ?> · <?= esc($item['published_at'] ?? ''); ?></p>
                            <h2 class="h5 card-title"><?= esc($item['judul']); ?></h2>
                            <p class="card-text text-muted"><?= esc($item['ringkasan']); ?></p>
                            <a class="btn btn-sm btn-primary" href="<?= base_url('artikel/' . esc($item['slug'], 'attr')); ?>">Baca artikel</a>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
        <a class="btn btn-link" href="<?= base_url('/'); ?>">Kembali ke beranda</a>
    </div>
</div>
<?= $this->endSection(); ?>
