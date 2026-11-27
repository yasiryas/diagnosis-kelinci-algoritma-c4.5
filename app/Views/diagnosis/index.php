<?= $this->extend('templates/public'); ?>

<?= $this->section('page-content'); ?>
<?php
$gejalaByKategori = [];
foreach ($gejala as $g) {
    $gejalaByKategori[$g['kategori']][] = $g;
}
?>
<section class="diagnosis-page diagnosis-form-page" aria-labelledby="diagnosis-title">
    <header class="diagnosis-intro">
        <p class="diagnosis-step" data-diagnosis-step="1">Langkah 1 dari 2</p>
        <h1 id="diagnosis-title">Perhatikan perubahan pada kelinci</h1>
        <p>Pilih tanda yang benar-benar terlihat. Hasilnya membantu mengenali kemungkinan kondisi, bukan memastikan diagnosis.</p>
        <p class="diagnosis-next-step">Setelah memilih gejala, Anda akan melihat kemungkinan kondisi dan saran penanganannya.</p>
    </header>

    <?php if (! empty($errors)) : ?>
        <div id="diagnosis-errors" class="diagnosis-error-summary" role="alert" tabindex="-1" aria-labelledby="diagnosis-error-title">
            <h2 id="diagnosis-error-title">Periksa pilihan gejala</h2>
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?= esc($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="diagnosis-form-card" action="<?= base_url('diagnosa/proses'); ?>" method="post">
        <?= csrf_field(); ?>
        <div class="diagnosis-form-heading">
            <h2>Gejala yang terlihat</h2>
            <p>Pilih satu atau beberapa gejala yang dialami kelinci.</p>
        </div>

        <?php if (empty($gejalaByKategori)) : ?>
            <p class="diagnosis-empty-state" role="status">Data gejala belum tersedia.</p>
        <?php else : ?>
            <?php foreach ($gejalaByKategori as $kategori => $items) : ?>
                <fieldset class="diagnosis-symptom-group">
                    <legend><?= esc($kategori); ?></legend>
                    <div class="diagnosis-symptoms-grid">
                        <?php foreach ($items as $g) : ?>
                            <?php $gejalaId = (string) $g['id_gejala']; ?>
                            <label class="diagnosis-symptom-card" for="gejala-<?= esc($gejalaId, 'attr'); ?>">
                                <input
                                    type="checkbox"
                                    name="gejala[]"
                                    value="<?= esc($gejalaId, 'attr'); ?>"
                                    id="gejala-<?= esc($gejalaId, 'attr'); ?>"
                                    <?= in_array($gejalaId, $selected, true) ? 'checked' : ''; ?>
                                >
                                <span><?= esc($g['gejala']); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="diagnosis-form-actions">
            <button type="submit" class="diagnosis-primary-action">Lihat kemungkinan hasil</button>
            <p>Jika kelinci tampak sangat lemah atau kondisinya memburuk, hubungi dokter hewan.</p>
        </div>
    </form>
</section>
<?= $this->endSection(); ?>
