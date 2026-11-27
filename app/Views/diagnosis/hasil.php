<?= $this->extend('templates/public'); ?>

<?= $this->section('page-content'); ?>
<section class="diagnosis-page diagnosis-result-page" aria-labelledby="diagnosis-result-title">
    <header class="diagnosis-intro">
        <p class="diagnosis-step" data-diagnosis-step="2">Langkah 2 dari 2</p>
        <h1 id="diagnosis-result-title">Kemungkinan hasil pemeriksaan</h1>
        <p>Berikut hasil yang paling sesuai dengan gejala yang dipilih.</p>
    </header>

    <article class="diagnosis-result-card">
        <div class="diagnosis-result-match">
            <p class="diagnosis-result-label">Kemungkinan kondisi</p>
            <h2><?= esc($hasil->penyakit); ?></h2>
        </div>

        <div class="diagnosis-result-section">
            <h3>Gejala yang cocok</h3>
            <p><?= esc($gejalaDetail); ?></p>
        </div>

        <div class="diagnosis-result-guidance">
            <section>
                <h3>Saran penanganan</h3>
                <p><?= esc($hasil->solusi); ?></p>
            </section>
            <section>
                <h3>Obat</h3>
                <p><?= esc($hasil->obat); ?></p>
            </section>
        </div>

        <p class="diagnosis-disclaimer">Hasil ini adalah skrining pendukung, bukan pengganti pemeriksaan dokter hewan. Konsultasikan kondisi kelinci kepada dokter hewan, terutama jika gejala berlanjut atau memburuk.</p>
    </article>

    <div class="diagnosis-result-actions">
        <a class="diagnosis-secondary-action" href="<?= base_url('diagnosa'); ?>">Periksa gejala lain</a>
        <?php if (user() === null) : ?>
            <a class="diagnosis-primary-action" href="<?= base_url('login'); ?>">Masuk ke akun</a>
        <?php else : ?>
            <form action="<?= base_url('user/diagnosis'); ?>" method="post">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_penyakit" value="<?= esc($hasil->id_penyakit, 'attr'); ?>">
                <button type="submit" class="diagnosis-primary-action">Simpan hasil ke riwayat</button>
            </form>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection(); ?>
