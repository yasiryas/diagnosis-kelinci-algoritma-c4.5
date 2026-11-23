<?= $this->extend('templates/public'); ?>

<?= $this->section('page-content'); ?>
<div class="row">
    <div class="col-lg-8 mx-auto">
        <h1 class="h3 mb-2 text-gray-800">Konsultasi Diagnosis</h1>
        <p class="text-muted mb-4">Pilih gejala yang dialami kelinci, lalu lihat kemungkinan penyakitnya.</p>
        <?php if (! empty($errors)) : ?>
            <div id="diagnosis-errors" class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    <?php foreach ($errors as $error) : ?>
                        <li><?= esc($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div class="card shadow-sm">
            <form action="<?= base_url('diagnosa/proses'); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="card-body">
                    <h2 class="h5 card-title">Pilih gejala sesuai yang dialami kelinci anda!</h2>
                    <?php if (empty($gejala)) : ?>
                        <p class="text-muted mb-0">Data gejala belum tersedia.</p>
                    <?php endif; ?>
                    <?php foreach ($gejala as $g) : ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="<?= esc($g['id_gejala'], 'attr'); ?>" name="gejala[]" id="gejala-<?= $g['id_gejala']; ?>" <?= in_array((string) $g['id_gejala'], $selected, true) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="gejala-<?= $g['id_gejala']; ?>">
                                <?= esc($g['kategori']); ?> <?= esc($g['gejala']); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary mt-3">Lihat Hasil Diagnosis</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
