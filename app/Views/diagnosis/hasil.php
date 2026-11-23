<?= $this->extend('templates/public'); ?>

<?= $this->section('page-content'); ?>
<div class="row">
    <div class="col-lg-8 mx-auto">
        <h1 class="h3 mb-4 text-gray-800">Hasil Diagnosis</h1>
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="small text-muted mb-1">Gejala yang dialami</p>
                <p class="mb-4"><?= esc($gejalaDetail); ?></p>
                <h2 class="h5 card-title">Kemungkinan penyakit: <?= esc($hasil->penyakit); ?></h2>
                <hr class="my-4">
                <h3 class="h6">Solusi</h3>
                <p><?= esc($hasil->solusi); ?></p>
                <h3 class="h6">Obat</h3>
                <p class="mb-0"><?= esc($hasil->obat); ?></p>
            </div>
        </div>
        <div class="d-flex flex-wrap align-items-center mt-4">
            <a class="btn btn-outline-primary mr-2 mb-2" href="<?= base_url('diagnosa'); ?>">Konsultasi lagi</a>
            <?php if (user() === null) : ?>
                <a class="btn btn-primary mb-2" href="<?= base_url('login'); ?>">Masuk ke akun</a>
            <?php else : ?>
                <form class="mb-2" action="<?= base_url('user/diagnosis'); ?>" method="post">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="id_penyakit" value="<?= esc($hasil->id_penyakit, 'attr'); ?>">
                    <button type="submit" class="btn btn-primary">Simpan riwayat</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
