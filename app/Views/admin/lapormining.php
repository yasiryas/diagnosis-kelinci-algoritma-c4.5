<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Lapor mining</h1>
    <?= $entropytotal; ?>
    <br>
    <?php foreach ($jumlahkasuspenyakit as $jkp) : ?>
        <?= $jkp->kategori; ?>-<?= $jkp->gejala; ?>-<?= $jkp->penyakit; ?>-<?= $jkp->total; ?><br>
    <?php endforeach; ?>
</div>

<?= $this->endSection(); ?>