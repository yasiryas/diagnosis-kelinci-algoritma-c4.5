<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="h3 mb-4 text-gray-800">Data Diagnosis</h1>
            <div class="alert alert-info" role="alert">
                Riwayat konsultasi tersedia pada dashboard. Pilih menu Dashboard untuk melihat ringkasan hasil.
            </div>
            <a href="<?= base_url('admin/'); ?>" class="btn btn-primary">Kembali ke dashboard</a>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
