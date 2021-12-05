<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <?php if (in_groups('admin')) : ?>
        <!-- Page Heading -->
        <div class="row">
            <div class="col-sm-3" id="dashboardAdmin">
                <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Total user yang terdaftar</h5>
                        <h3 class="card-text"><?= $user ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Total gejala yang terdaftar</h5>
                        <h3 class="card-text"><?= $gejala; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Total penyakit yang terdaftar</h5>
                        <h3 class="card-text"><?= $penyakit; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Total rekaman konsultasi</h5>
                        <h3 class="card-text"><?= $hasil; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif (in_groups('user')) : ?>
        <div class="row">
            <div class="col-sm-3">
                <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Total gejala yang terdaftar</h5>
                        <h3 class="card-text"><?= $gejala; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Total penyakit yang terdaftar</h5>
                        <h3 class="card-text"><?= $penyakit; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Total rekaman konsultasi</h5>
                        <h3 class="card-text"><?= $hasil; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>