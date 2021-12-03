<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Page Heading -->
            <!-- <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1> -->
            <?php if (session()->getFlashdata('pesan')) : ?>
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('pesan'); ?>
                </div>
            <?php endif; ?>

            <!-- jumbotron -->
            <div class="jumbotron">
                <h1 class="display-4"><?= $title; ?></h1>
                <?php //foreach ($hasil as $h) :
                ?>
                <p class="lead">Gejala yang dialami : <b><?= $gejala; ?></b></p>
                <h5>Kelinci anda mengidap penyakit <b><?= $hasil->penyakit; ?></b></h5>
                <?php // endforeach;
                ?>
                <hr class="my-4">
                <p>Dari hasil diagnosis kami memberikan saran solusi sebagai berikut:
                    <br>
                    <?= $hasil->solusi; ?>
                    <br>
                    <hr class="my-3">
                    Untuk mempercepat penyembuhan, kelinci anda dapat diberikan obat:
                    <br>
                    <?= $hasil->obat; ?>
                    <hr class="my-3">
                </p><br>
                <p class="lead">
                    <a class="btn btn-primary" href="<?= base_url('user/prosesdiagnosis'); ?>">
                        Konsultasi Lagi
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>