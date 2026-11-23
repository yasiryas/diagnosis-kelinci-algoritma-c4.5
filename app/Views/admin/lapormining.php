<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Laporan Mining</h1>
    <div class="alert alert-info" role="alert">
        Rincian perhitungan gain ratio tersedia pada halaman hasil mining.
    </div>
    <a href="<?= base_url('mining/hitung'); ?>" class="btn btn-primary">Lihat hasil mining</a>
    <form action="<?= base_url('mining/kosong'); ?>" method="post" class="d-inline" data-ajax-form data-ajax-url="<?= base_url('mining/kosong'); ?>" data-ajax-method="post" data-ajax-action="clear" data-ajax-redirect="<?= base_url('admin/datasample'); ?>">
        <?= csrf_field(); ?>
        <button type="submit" class="btn btn-outline-danger">Kosongkan data sample</button>
    </form>
</div>

<?= $this->endSection(); ?>
