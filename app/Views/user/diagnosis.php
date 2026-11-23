<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <?php if (session()->getFlashdata('pesan')) : ?>
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('pesan'); ?>
                </div>
            <?php endif; ?>

            <div class="jumbotron">
                <h1 class="display-4"><?= $title; ?></h1>
                <p class="lead">Diagnosis penyakit yang dialami kelinci anda sekarang!</p>
                <hr class="my-4">
                <p>Untuk memulai diagnosis silahkan tekan tombol <b>mulai konsultasi</b>.</p>
                <p class="lead">
                    <a class="btn btn-primary" href="<?= base_url('user/prosesdiagnosis'); ?>">
                        Mulai Konsultasi
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
