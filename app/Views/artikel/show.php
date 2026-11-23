<?= $this->extend('templates/public'); ?>

<?= $this->section('page-content'); ?>
<div class="row">
    <div class="col-lg-8 mx-auto">
        <a class="btn btn-link pl-0" href="<?= base_url('artikel'); ?>">← Kembali ke daftar artikel</a>
        <?php if ($item === null) : ?>
            <div class="card shadow-sm">
                <div class="card-body text-center text-muted py-5">Artikel tidak ditemukan.</div>
            </div>
        <?php else : ?>
            <article class="card shadow-sm">
                <div class="card-body">
                    <p class="small text-primary mb-2"><?= esc($item['nama_kategori'] ?? ''); ?> · <?= esc($item['published_at'] ?? ''); ?></p>
                    <h1 class="h3 mb-4 text-gray-800"><?= esc($item['judul']); ?></h1>
                    <p class="lead text-muted"><?= esc($item['ringkasan']); ?></p>
                    <div class="mt-4"><?= nl2br(esc($item['konten'])); ?></div>
                </div>
            </article>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection(); ?>
